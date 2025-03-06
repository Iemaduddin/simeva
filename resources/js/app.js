import "./bootstrap";
let userIdMeta = document.querySelector("meta[name='user-id']");
if (!userIdMeta) {
    console.error("Meta tag user-id tidak ditemukan!");
} else {
    let userId = userIdMeta.getAttribute("content");
    console.log("User ID ditemukan:", userId);
    console.log("window echo: ", window.Echo);

    if (userId) {
        window.Echo.channel(`App.Models.User.${userId}`).notification(
            (notification) => {
                console.log("📩 Notifikasi diterima:", notification);

                let notifList = document.getElementById("notif-list");
                let notifCount = document.getElementById("notif-count");

                if (!notifList || !notifCount) {
                    console.error(
                        "❌ Element notif-list atau notif-count tidak ditemukan!"
                    );
                    return;
                }

                // Buat elemen notifikasi baru
                let newNotif = document.createElement("a");
                newNotif.href = notification.link ?? "javascript:void(0)";
                newNotif.classList.add(
                    "px-24",
                    "py-12",
                    "d-flex",
                    "align-items-start",
                    "gap-3",
                    "mb-2",
                    "justify-content-between",
                    "bg-primary-25"
                );

                newNotif.innerHTML = `
                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                    <span class="w-44-px h-44-px bg-info-subtle text-info-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                        <iconify-icon icon="iconoir:bell" class="icon text-xxl"></iconify-icon>
                    </span>
                    <div>
                        <h6 class="text-md fw-semibold mb-4">${
                            notification.title ?? "Notifikasi Baru"
                        }</h6>
                        <p class="mb-0 text-sm text-secondary-light text-w-200-px">
                            ${notification.message ?? "Tidak ada pesan"}
                        </p>
                    </div>
                </div>
                <span class="text-sm text-secondary-light flex-shrink-0">
                    baru saja
                </span>
            `;

                // Tambahkan notifikasi baru ke dropdown
                notifList.prepend(newNotif);

                // Update jumlah notifikasi
                let count = parseInt(notifCount.innerText) || 0;
                notifCount.innerText = count + 1;
            }
        );
    } else {
        console.error("❌ User ID tidak valid!");
    }
}
