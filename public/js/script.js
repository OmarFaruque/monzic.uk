window.aiDocumentCallback = function() {
    return {
        docPrompt: '',
        loading: false,
        generatedDoc: '',
        uuid: '',
        async init() {

            try {

                const res = await fetch('/pp/paddle/token', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    credentials: 'same-origin',
                });

                const data = await res.json();

                if (!data.token) {
                    console.error("❌ No Paddle token received");
                    return;
                }


                Paddle.Environment.set("sandbox"); // Remove for live
                Paddle.Initialize({
                    token: data.token, 
                    eventCallback: function (event) {
                        if (event.type === "checkout.error") {
                            console.error("Paddle Checkout Error:", event);
                        }
                    }
                });
            } catch (e) {
                console.error("Paddle init error:", e);
            }
        },
        async editRequest(){
            this.generatedDoc = '';
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

                    this.generatedDoc = data?.content || '⚠️ No response';
                    this.uuid = data?.uuid || 'No Response';
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


            var itemsList = [
                {
                    priceId: data.price_id,
                    quantity: 1
                }
            ];


            try {
                Paddle.Checkout.open({
                    settings: {
                        displayMode: "overlay",
                        theme: "light",
                        locale: "en",
                        successUrl: data.success_url
                    },
                    items: itemsList,
                    customData: {
                        doc_uuid: this.uuid
                    },
                });
                } catch (error) {
                        console.error("❌ Checkout threw error:", error);
                }

            // Redirect to Paddle Pay Link
            // window.location.href = data.url;
        }

    }
}