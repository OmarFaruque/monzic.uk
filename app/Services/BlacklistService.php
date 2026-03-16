<?php

namespace App\Services;

use App\Models\BlackList;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BlacklistService
{
    public function normalizeText(?string $value): string
    {
        return mb_strtolower(trim((string) $value));
    }

    public function normalizeRegistration(?string $value): string
    {
        return preg_replace('/\s+/', '', $this->normalizeText($value));
    }

    public function normalizeEmail(?string $value): string
    {
        return preg_replace('/\s+/', '', $this->normalizeText($value));
    }

    public function isBlacklisted(array $payload): bool
    {
        $normalized = $this->normalizePayload($payload);
        $entries = $this->getCandidateEntries($normalized);

        foreach ($entries as $entry) {

            $matches = json_decode($entry->matches, true);
            if (!is_array($matches)) {
                continue;
            }

            if ($this->matchesEntry($matches, $normalized)) {
                return true;
            }
        }

        return false;
    }

    public function isUserBlacklisted(User $user): bool
    {
        return $this->isBlacklisted([
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'birth_date' => $user->date_of_birth ?? null,
        ]);
    }

    private function getCandidateEntries(array $normalized)
    {
        $filters = array_filter([
            $normalized['email'] ?? null,
            $normalized['first_name'] ?? null,
            $normalized['last_name'] ?? null,
            $normalized['birth_date'] ?? null,
            $normalized['reg_number'] ?? null,
        ]);

        if (empty($filters)) {
            return collect();
        }

        return BlackList::query()
            ->where(function ($query) use ($normalized) {
                if (!empty($normalized['email'])) {
                    $query->orWhere('matches', 'like', '%"email":"' . $normalized['email'] . '"%');
                    // Also try with spaces if the stored data might have them
                    $query->orWhere('matches', 'like', '%"email":"' . str_replace('@', ' @', $normalized['email']) . '"%');
                }
                if (!empty($normalized['first_name'])) {
                    $query->orWhere('matches', 'like', '%"first_name":"' . $normalized['first_name'] . '"%');
                }
                if (!empty($normalized['last_name'])) {
                    $query->orWhere('matches', 'like', '%"last_name":"' . $normalized['last_name'] . '"%');
                }
                if (!empty($normalized['birth_date'])) {
                    $query->orWhere('matches', 'like', '%"birth_date":"' . $normalized['birth_date'] . '%');
                }
                if (!empty($normalized['reg_number'])) {
                    $query->orWhere('matches', 'like', '%"registrations":"%' . $normalized['reg_number'] . '%"%');
                }
            })
            ->get();
    }

    private function normalizePayload(array $payload): array
    {
        return [
            'email' => $this->normalizeEmail($payload['email'] ?? ''),
            'first_name' => $this->normalizeText($payload['first_name'] ?? ''),
            'last_name' => $this->normalizeText($payload['last_name'] ?? ''),
            'birth_date' => $this->normalizeBirthDate($payload['birth_date'] ?? null),
            'reg_number' => $this->normalizeRegistration($payload['reg_number'] ?? ''),
        ];
    }

    private function normalizeBirthDate(?string $value): string
    {
        if (empty($value)) {
            return '';
        }

        $value = trim($value);

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)) {
            [$day, $month, $year] = explode('-', $value);
            return sprintf('%04d-%02d-%02d', (int) $year, (int) $month, (int) $day);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return '';
        }

        return date('Y-m-d', $timestamp);
    }



    private function matchesEntry(array $matches, array $payload): bool
    {
        $comparedFields = 0;

        if (isset($matches['birth_date'])) {
            // Login payloads may not include DOB; in that case, ignore DOB as a hard blocker
            // and still evaluate other identifiers (email/name/registration).
            if (!empty($payload['birth_date'])) {
                if (!$this->birthDateMatches($matches['birth_date'], $payload['birth_date'])) {
                    return false;
                }
                $comparedFields++;
            }
        }

        if (isset($matches['last_name'])) {
            if (empty($payload['last_name']) || $this->normalizeText($matches['last_name']) !== $payload['last_name']) {
                return false;
            }
            $comparedFields++;
        }

        if (isset($matches['first_name'])) {
            if (empty($payload['first_name']) || $this->normalizeText($matches['first_name']) !== $payload['first_name']) {
                return false;
            }
            $comparedFields++;
        }

        if (isset($matches['email'])) {
            if (empty($payload['email']) || $this->normalizeText($matches['email']) !== $payload['email']) {
                return false;
            }
            $comparedFields++;
        }

        if (isset($matches['registrations'])) {
            if (empty($payload['reg_number']) || !$this->registrationMatches($matches['registrations'], $payload['reg_number'])) {
                return false;
            }
            $comparedFields++;
        }

        return $comparedFields > 0;
    }


    private function birthDateMatches(string $blacklistBirthDate, string $birthDate): bool
    {
        if ($birthDate === '') {
            return false;
        }

        $ruleParts = explode('-', $blacklistBirthDate);
        $valueParts = explode('-', $birthDate);

        if (!isset($ruleParts[0], $valueParts[0]) || $ruleParts[0] !== $valueParts[0]) {
            return false;
        }

        if (isset($ruleParts[1]) && isset($valueParts[1]) && (int) $ruleParts[1] !== (int) $valueParts[1]) {
            return false;
        }

        if (isset($ruleParts[2]) && isset($valueParts[2]) && (int) $ruleParts[2] !== (int) $valueParts[2]) {
            return false;
        }

        return true;
    }

    private function registrationMatches(string $registrations, string $regNumber): bool
    {
        if ($regNumber === '') {
            return false;
        }

        $regData = [];
        foreach (explode(',', $registrations) as $registration) {
            $normalized = $this->normalizeRegistration($registration);
            if ($normalized !== '') {
                $regData[] = $normalized;
            }
        }

        return in_array($regNumber, $regData, true);
    }
}