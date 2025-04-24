@if (auth()->check() && auth()->user()->hasRole('Participant'))
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const eventButtons = document.querySelectorAll(".wishlist-btn");
            const eventIds = Array.from(eventButtons).map(button => button.dataset.eventId);

            try {
                const response = await fetch("{{ route('saved.item.check') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        itemType: 'event',
                        event_ids: eventIds
                    })
                });

                const savedEvents = await response.json();

                eventButtons.forEach(button => {
                    if (savedEvents.includes(button.dataset.eventId)) {
                        button.classList.add("bg-main-two-600", "text-white");
                        button.classList.remove("bg-white", "text-main-two-600");
                    } else {
                        button.classList.add("bg-white", "text-main-two-600");
                        button.classList.remove("bg-main-two-600", "text-white");
                    }
                });

            } catch (error) {
                console.error("Error fetching saved:", error);
            }

            eventButtons.forEach(button => {
                button.addEventListener("click", async function() {
                    const eventId = this.dataset.eventId;
                    const isEventSaved = this.classList.contains("bg-main-two-600");

                    try {
                        const response = await fetch(
                            "{{ route('saved.item.toggle') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({
                                    itemType: 'event',
                                    event_id: eventId
                                })
                            });

                        const result = await response.json();

                        if (response.ok) {
                            if (isEventSaved) {
                                this.classList.add("bg-white", "text-main-two-600");
                                this.classList.remove("bg-main-two-600", "text-white");
                            } else {
                                this.classList.add("bg-main-two-600", "text-white");
                                this.classList.remove("bg-white", "text-main-two-600");
                            }
                            this.style.transform = "scale(1.2)";
                            setTimeout(() => this.style.transform = "scale(1)", 200);
                        } else {
                            console.error("Error:", result.message);
                        }
                    } catch (error) {
                        console.error("Request failed:", error);
                    }
                });
            });
        });
    </script>
@endif
