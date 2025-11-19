document.addEventListener("DOMContentLoaded", () => {
    const sendOtpBtn = document.getElementById("sendOtpBtn");
    const verifyOtpBtn = document.getElementById("verifyOtpBtn");
    const otpSection = document.getElementById("otpSection");
    const otpStatus = document.getElementById("otpStatus");
    const emailInput = document.getElementById("email");
    const registerBtn = document.getElementById("registerBtn");

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    // ✅ SEND OTP
    sendOtpBtn.addEventListener("click", async () => {
        const email = emailInput.value.trim();
        if (!email) {
            alert("Please enter your San Beda email first.");
            return;
        }

        sendOtpBtn.disabled = true;
        sendOtpBtn.textContent = "Sending...";

        try {
            const response = await fetch("/send-otp", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ email }),
            });

            const data = await response.json();

            if (data.success) {
                otpSection.style.display = "block";
                otpStatus.textContent = "OTP sent successfully to your email.";
                otpStatus.style.color = "green";

                // ✅ Keep button disabled and show timer
                let countdown = 60;
                sendOtpBtn.textContent = `Resend in ${countdown}s`;

                const timer = setInterval(() => {
                    countdown--;
                    sendOtpBtn.textContent = `Resend in ${countdown}s`;

                    if (countdown <= 0) {
                        clearInterval(timer);
                        sendOtpBtn.disabled = false;
                        sendOtpBtn.textContent = "Resend OTP";
                    }
                }, 1000);
            } else {
                otpStatus.textContent = data.message || "Failed to send OTP.";
                otpStatus.style.color = "red";
                sendOtpBtn.disabled = false;
                sendOtpBtn.textContent = "Send OTP";
            }
        } catch (error) {
            console.error("Error sending OTP:", error);
            otpStatus.textContent = "Something went wrong. Please try again.";
            otpStatus.style.color = "red";
            sendOtpBtn.disabled = false;
            sendOtpBtn.textContent = "Send OTP";
        }
    });

    // ✅ VERIFY OTP
    verifyOtpBtn.addEventListener("click", async () => {
        const email = emailInput.value.trim();
        const otp = document.getElementById("otpInput").value.trim();

        if (!otp) {
            otpStatus.textContent = "Please enter the OTP.";
            otpStatus.style.color = "red";
            return;
        }

        verifyOtpBtn.disabled = true;
        verifyOtpBtn.textContent = "verified";

        try {
            const response = await fetch("/verify-otp", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ email, otp }),
            });

            const data = await response.json();

            if (data.success) {
                otpStatus.textContent = "OTP verified successfully!";
                otpStatus.style.color = "green";
                registerBtn.disabled = false;
                sendOtpBtn.disabled = true; // ✅ keep disabled after successful verify
                sendOtpBtn.textContent = "OTP Verified ✓";
            } else {
                otpStatus.textContent = data.message || "Invalid OTP.";
                otpStatus.style.color = "red";
                verifyOtpBtn.disabled = false;
                verifyOtpBtn.textContent = "Verify OTP";
            }
        } catch (error) {
            console.error("Error verifying OTP:", error);
            otpStatus.textContent = "Something went wrong. Please try again.";
            otpStatus.style.color = "red";
            verifyOtpBtn.disabled = false;
            verifyOtpBtn.textContent = "Verify OTP";
        }
    });
});
