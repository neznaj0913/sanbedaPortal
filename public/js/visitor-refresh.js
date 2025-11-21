document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.querySelector("#visitorTable tbody");
    const totalVisitors = document.getElementById("total-visitors");
    const currentlyInside = document.getElementById("currently-inside");
    const checkedOut = document.getElementById("checked-out");

    const REFRESH_INTERVAL = 5000; 
    let isFetching = false;

    async function fetchVisitors() {
        if (isFetching) return;
        isFetching = true;

        try {
            const response = await fetch("/fetch-history-visitors", {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
            });

            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

            const data = await response.json();

            if (tableBody && Array.isArray(data.visitors)) {
                const visitors = data.visitors;

                const rows = visitors.length
                    ? visitors
                          .map((v) => {
                              const formatTime = (dateString) => {
                                  if (!dateString) return "—";
                                  const date = new Date(dateString);
                                  return date.toLocaleString("en-PH", {
                                      timeZone: "Asia/Manila",
                                      year: "numeric",
                                      month: "2-digit",
                                      day: "2-digit",
                                      hour: "numeric",
                                      minute: "2-digit",
                                      second: "2-digit",
                                      hour12: true,
                                  });
                              };

                              const timeIn = formatTime(v.time_in);
                              const timeOut = formatTime(v.time_out);

                              const statusClass = v.time_out
                                  ? "timed-out"
                                  : v.time_in
                                  ? "inside"
                                  : "pending";
                              const statusText = v.time_out
                                  ? "Timed Out"
                                  : v.time_in
                                  ? "Inside"
                                  : "Pending";

                              return `
                                <tr data-visitor-id="${v.id}">
                                    <td>${v.first_name ?? ""} ${v.last_name ?? ""}</td>
                                    <td>${v.department ?? "—"}</td>
                                    <td>${v.purpose ?? "—"}</td>
                                    <td>${v.gatepass_no ?? "—"}</td>
                                    <td>${timeIn}</td>
                                    <td>${timeOut}</td>
                                    <td><span class="status ${statusClass}">${statusText}</span></td>
                                </tr>
                            `;
                          })
                          .join("")
                    : `<tr><td colspan="7" class="no-data">No visitors found.</td></tr>`;

                tableBody.innerHTML = rows;
            }

            // Update statistics if they exist
            if (totalVisitors && currentlyInside && checkedOut) {
                totalVisitors.textContent = data.totalVisitors ?? 0;
                currentlyInside.textContent = data.currentlyInside ?? 0;
                checkedOut.textContent = data.checkedOut ?? 0;
            }
        } catch (error) {
            console.error("❌ Error fetching visitors:", error);
        } finally {
            isFetching = false;
        }
    }

    fetchVisitors();
    const refreshInterval = setInterval(fetchVisitors, REFRESH_INTERVAL);

    window.addEventListener("beforeunload", () => clearInterval(refreshInterval));
});
