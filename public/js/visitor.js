document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ visitor.js loaded");

  
    const formContainer = document.getElementById("visitorForm");
    const overlay = document.getElementById("accessOverlay");

    if (overlay) {
        formContainer.classList.add("blurred");
    } else {
        formContainer.classList.remove("blurred");
    }

 
    const checkbox = document.getElementById("setOtherDay");
    const appointmentSection = document.getElementById("appointmentSection");
    const appointmentInput = document.getElementById("appointment_time");

    if (checkbox && appointmentSection && appointmentInput) {

        const getLocalDateTime = () => {
            const now = new Date();
            return new Date(now.getTime() - now.getTimezoneOffset() * 60000)
                .toISOString()
                .slice(0, 16);
        };

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
    }

  
    const purposeDropdown = document.getElementById("purpose");
    const otherPurposeGroup = document.getElementById("otherPurposeGroup");
    const otherPurposeInput = document.getElementById("other_purpose");

    if (purposeDropdown && otherPurposeGroup && otherPurposeInput) {
        purposeDropdown.addEventListener("change", () => {
            if (purposeDropdown.value === "Other") {
                otherPurposeGroup.style.display = "block";
                otherPurposeInput.required = true;
            } else {
                otherPurposeGroup.style.display = "none";
                otherPurposeInput.required = false;
                otherPurposeInput.value = "";
            }
        });
    }

 
    const modal = document.getElementById("privacyModal");
    const openLink = document.getElementById("openPrivacyModal");
    const closeBtn = document.querySelector(".close-modal");

    if (modal && openLink && closeBtn) {
        openLink.addEventListener("click", (e) => {
            e.preventDefault();
            modal.style.display = "flex";
        });

        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    }


    const visitorForm = document.getElementById("visitorForm");
    if (visitorForm) {
        visitorForm.addEventListener("submit", () => {
            console.log("Form is submitting...");
            formContainer.classList.remove("blurred");
        });
    }
});
