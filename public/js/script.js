window.aiDocumentCallback = function() {
    return {
        docPrompt: '',
        loading: false,
        generatedDoc: '',
        init() {
            console.log('init function run');
        },
        async generateDocument() {
                if (!this.docPrompt.trim()) return;

                this.loading = true;
                this.generatedDoc = '';

                try {
                    const response = await fetch('/generate-ai-document', {
                        method: 'POST',
                        credentials: 'same-origin', 
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ prompt: this.docPrompt })
                    });

                    const data = await response.json();

                    

                    this.generatedDoc = data.choices?.[0]?.message?.content || '⚠️ No response';
                    console.log('return data: ', this.generatedDoc);
                } catch (error) {
                    console.error(error);
                    this.generatedDoc = '❌ Error generating document.';
                }

                this.loading = false;
        }, 
        async handlePaddleCheckout() {
            if (!this.generatedDoc) return alert("Generate the document first.");


            const button = event.currentTarget;

            const price = parseFloat(button.dataset.price);
            const title = button.dataset.title || "Untitled Document";
            const email = button.dataset.email || null;


            const res = await fetch('/generate-pay-link', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            });

            const data = await res.json();

            if (data.error) return alert("❌ " + data.error);


            console.log('return data: ', data)

            var itemsList = [
                {
                    priceId: data.price_id,
                    quantity: 1
                }
            ];

            console.log('itemslist: ', itemsList);

                try {
                    Paddle.Checkout.open({
                        settings: {
                        displayMode: "overlay",
                        theme: "light",
                        locale: "en"
                        },
                        items: itemsList
                    });
                    } catch (error) {
                         console.error("❌ Checkout threw error:", error);
                    }

            // Redirect to Paddle Pay Link
            // window.location.href = data.url;
        }

    }
}