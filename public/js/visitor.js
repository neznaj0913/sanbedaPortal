document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ visitor.js loaded");

    const formContainer = document.getElementById("visitorForm");
    const overlay = document.getElementById("accessOverlay");

    // Blur the form only if overlay exists and access is not granted
    if (overlay) {
        formContainer.classList.add("blurred");
    } else {
        formContainer.classList.remove("blurred");
    }

    // ===== Appointment Section Logic =====
    const checkbox = document.getElementById("setOtherDay");
    const appointmentSection = document.getElementById("appointmentSection");
    const appointmentInput = document.getElementById("appointment_time");

    if (!checkbox || !appointmentSection || !appointmentInput) {
        console.warn("⚠️ Missing elements for appointment script.");
        return;
    }

    // Helper: get local datetime in YYYY-MM-DDTHH:mm
    const getLocalDateTime = () => {
        const now = new Date();
        return new Date(now.getTime() - now.getTimezoneOffset() * 60000)
            .toISOString()
            .slice(0, 16);
    };

    // Initialize appointment input
    const now = getLocalDateTime();
    appointmentInput.min = now;
    appointmentInput.value = now;
    appointmentInput.required = false;
    appointmentSection.classList.remove("active");

    appointmentInput.addEventListener("change", () => {
        if (appointmentInput.value < appointmentInput.min) {
            alert("⚠️ Please select today or a future date/time only.");
            appointmentInput.value = appointmentInput.min;
        }
    });

    checkbox.addEventListener("change", () => {
        if (checkbox.checked) {
            appointmentSection.classList.add("active");
            appointmentInput.required = true;
        } else {
            appointmentSection.classList.remove("active");
            appointmentInput.required = false;
            appointmentInput.value = getLocalDateTime();
        }
    });

    const visitorForm = document.getElementById("visitorForm");
    visitorForm.addEventListener("submit", (e) => {
        formContainer.classList.remove("blurred");
    });
});
const visitorForm = document.getElementById("visitorForm");

if (visitorForm) {
    visitorForm.addEventListener("submit", (e) => {
        console.log("Form is submitting...");
    });
}
