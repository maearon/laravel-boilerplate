// Custom JavaScript for the application
document.addEventListener("DOMContentLoaded", () => {
    // Add any custom JavaScript functionality here

    // Example: Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll(".alert")
    alerts.forEach((alert) => {
      setTimeout(() => {
        const closeButton = alert.querySelector(".btn-close")
        if (closeButton) {
          closeButton.click()
        }
      }, 5000)
    })
  })
