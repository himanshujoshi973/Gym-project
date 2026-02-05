/**
 * GRANDE FITNESS - SHARED PROFESSIONAL JAVASCRIPT
 * Modern animations, form validation, and interactive components
 */

class GrandeFitnessApp {
  constructor() {
    this.init()
  }

  init() {
    this.setupAnimations()
    this.setupFormValidation()
    this.setupInteractiveElements()
    this.setupLoadingStates()
    this.setupNotifications()
    console.log("[v0] Grande Fitness Professional UI initialized")
  }

  // ==========================================================================
  // ANIMATION SYSTEM
  // ==========================================================================

  setupAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: "0px 0px -50px 0px",
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("animate-fade-in")
          observer.unobserve(entry.target)
        }
      })
    }, observerOptions)

    // Observe all cards and stat elements
    document.querySelectorAll(".card, .stat-card, .table-container").forEach((el) => {
      observer.observe(el)
    })

    // Stagger animations for multiple elements
    this.staggerAnimations(".card", "animate-slide-in-left", 100)
    this.staggerAnimations(".stat-card", "animate-scale-in", 150)
  }

  staggerAnimations(selector, animationClass, delay) {
    const elements = document.querySelectorAll(selector)
    elements.forEach((el, index) => {
      setTimeout(() => {
        el.classList.add(animationClass)
      }, index * delay)
    })
  }

  // Smooth scroll to element
  scrollToElement(elementId) {
    const element = document.getElementById(elementId)
    if (element) {
      element.scrollIntoView({
        behavior: "smooth",
        block: "start",
      })
    }
  }

  // ==========================================================================
  // FORM VALIDATION SYSTEM
  // ==========================================================================

  setupFormValidation() {
    const forms = document.querySelectorAll("form")
    forms.forEach((form) => {
      this.initializeForm(form)
    })
  }

  initializeForm(form) {
    const inputs = form.querySelectorAll(".form-input, .form-select, .form-textarea")

    inputs.forEach((input) => {
      // Real-time validation
      input.addEventListener("blur", () => this.validateField(input))
      input.addEventListener("input", () => this.clearFieldError(input))
    })

    // Form submission
    form.addEventListener("submit", (e) => {
      if (!this.validateForm(form)) {
        e.preventDefault()
        this.showFormErrors(form)
      } else {
        this.showLoadingState(form)
      }
    })
  }

  validateField(field) {
    const value = field.value.trim()
    const fieldType = field.type || field.tagName.toLowerCase()
    let isValid = true
    let errorMessage = ""

    // Remove existing error state
    this.clearFieldError(field)

    // Required field validation
    if (field.hasAttribute("required") && !value) {
      isValid = false
      errorMessage = "This field is required"
    }

    // Email validation
    if (fieldType === "email" && value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailRegex.test(value)) {
        isValid = false
        errorMessage = "Please enter a valid email address"
      }
    }

    // Phone validation
    if (field.name === "phone" && value) {
      const phoneRegex = /^[+]?[1-9][\d]{0,15}$/
      if (!phoneRegex.test(value.replace(/[\s\-$$$$]/g, ""))) {
        isValid = false
        errorMessage = "Please enter a valid phone number"
      }
    }

    // Password validation
    if (fieldType === "password" && value) {
      if (value.length < 6) {
        isValid = false
        errorMessage = "Password must be at least 6 characters long"
      }
    }

    // Confirm password validation
    if (field.name === "confirm_password" && value) {
      const passwordField = field.form.querySelector('input[name="password"]')
      if (passwordField && value !== passwordField.value) {
        isValid = false
        errorMessage = "Passwords do not match"
      }
    }

    if (!isValid) {
      this.showFieldError(field, errorMessage)
    }

    return isValid
  }

  validateForm(form) {
    const fields = form.querySelectorAll(".form-input, .form-select, .form-textarea")
    let isFormValid = true

    fields.forEach((field) => {
      if (!this.validateField(field)) {
        isFormValid = false
      }
    })

    return isFormValid
  }

  showFieldError(field, message) {
    field.classList.add("error")

    // Remove existing error message
    const existingError = field.parentNode.querySelector(".field-error")
    if (existingError) {
      existingError.remove()
    }

    // Add new error message
    const errorElement = document.createElement("div")
    errorElement.className = "field-error"
    errorElement.style.cssText = `
      color: var(--accent-red);
      font-size: var(--text-xs);
      margin-top: var(--space-1);
      animation: fadeIn 0.3s ease-out;
    `
    errorElement.textContent = message
    field.parentNode.appendChild(errorElement)
  }

  clearFieldError(field) {
    field.classList.remove("error")
    const errorElement = field.parentNode.querySelector(".field-error")
    if (errorElement) {
      errorElement.remove()
    }
  }

  showFormErrors(form) {
    const firstError = form.querySelector(".error")
    if (firstError) {
      firstError.focus()
      this.scrollToElement(firstError.id || firstError.name)
    }
  }

  showLoadingState(form) {
    const submitButton = form.querySelector('button[type="submit"], input[type="submit"]')
    if (submitButton) {
      const originalText = submitButton.textContent
      submitButton.disabled = true
      submitButton.innerHTML = `
        <span class="loading-spinner"></span>
        Processing...
      `

      // Reset after 3 seconds (adjust based on actual form processing time)
      setTimeout(() => {
        submitButton.disabled = false
        submitButton.textContent = originalText
      }, 3000)
    }
  }

  // ==========================================================================
  // INTERACTIVE ELEMENTS
  // ==========================================================================

  setupInteractiveElements() {
    // Enhanced button interactions
    this.setupButtonEffects()

    // Card hover effects
    this.setupCardEffects()

    // Table interactions
    this.setupTableEffects()

    // Modal functionality
    this.setupModals()

    // Dropdown functionality
    this.setupDropdowns()
  }

  setupButtonEffects() {
    const buttons = document.querySelectorAll(".btn")
    buttons.forEach((button) => {
      button.addEventListener("click", (e) => {
        // Ripple effect
        this.createRippleEffect(e, button)
      })
    })
  }

  createRippleEffect(event, element) {
    const ripple = document.createElement("span")
    const rect = element.getBoundingClientRect()
    const size = Math.max(rect.width, rect.height)
    const x = event.clientX - rect.left - size / 2
    const y = event.clientY - rect.top - size / 2

    ripple.style.cssText = `
      position: absolute;
      width: ${size}px;
      height: ${size}px;
      left: ${x}px;
      top: ${y}px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      transform: scale(0);
      animation: ripple 0.6s ease-out;
      pointer-events: none;
    `

    // Add ripple keyframe if not exists
    if (!document.querySelector("#ripple-styles")) {
      const style = document.createElement("style")
      style.id = "ripple-styles"
      style.textContent = `
        @keyframes ripple {
          to {
            transform: scale(2);
            opacity: 0;
          }
        }
      `
      document.head.appendChild(style)
    }

    element.style.position = "relative"
    element.style.overflow = "hidden"
    element.appendChild(ripple)

    setTimeout(() => {
      ripple.remove()
    }, 600)
  }

  setupCardEffects() {
    const cards = document.querySelectorAll(".card, .stat-card")
    cards.forEach((card) => {
      card.addEventListener("mouseenter", () => {
        card.style.transform = "translateY(-4px) scale(1.02)"
      })

      card.addEventListener("mouseleave", () => {
        card.style.transform = "translateY(0) scale(1)"
      })
    })
  }

  setupTableEffects() {
    const tables = document.querySelectorAll(".table")
    tables.forEach((table) => {
      const rows = table.querySelectorAll("tbody tr")
      rows.forEach((row) => {
        row.addEventListener("click", () => {
          // Remove active class from other rows
          rows.forEach((r) => r.classList.remove("active"))
          // Add active class to clicked row
          row.classList.add("active")
        })
      })
    })

    // Add active row styles
    if (!document.querySelector("#table-styles")) {
      const style = document.createElement("style")
      style.id = "table-styles"
      style.textContent = `
        .table tbody tr.active {
          background-color: rgba(6, 182, 212, 0.1);
          border-left: 4px solid var(--primary-500);
        }
      `
      document.head.appendChild(style)
    }
  }

  setupModals() {
    // Modal trigger buttons
    document.addEventListener("click", (e) => {
      if (e.target.matches("[data-modal-target]")) {
        const modalId = e.target.getAttribute("data-modal-target")
        this.openModal(modalId)
      }

      if (e.target.matches("[data-modal-close]")) {
        this.closeModal(e.target.closest(".modal"))
      }
    })

    // Close modal on backdrop click
    document.addEventListener("click", (e) => {
      if (e.target.matches(".modal-backdrop")) {
        this.closeModal(e.target.querySelector(".modal"))
      }
    })

    // Close modal on Escape key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        const openModal = document.querySelector(".modal.show")
        if (openModal) {
          this.closeModal(openModal)
        }
      }
    })
  }

  openModal(modalId) {
    const modal = document.getElementById(modalId)
    if (modal) {
      modal.classList.add("show")
      document.body.style.overflow = "hidden"

      // Focus first input in modal
      const firstInput = modal.querySelector(".form-input, .form-select, .form-textarea")
      if (firstInput) {
        setTimeout(() => firstInput.focus(), 100)
      }
    }
  }

  closeModal(modal) {
    if (modal) {
      modal.classList.remove("show")
      document.body.style.overflow = ""
    }
  }

  setupDropdowns() {
    document.addEventListener("click", (e) => {
      if (e.target.matches("[data-dropdown-toggle]")) {
        const dropdownId = e.target.getAttribute("data-dropdown-toggle")
        const dropdown = document.getElementById(dropdownId)
        if (dropdown) {
          dropdown.classList.toggle("show")
        }
      }

      // Close dropdowns when clicking outside
      if (!e.target.closest("[data-dropdown-toggle]") && !e.target.closest(".dropdown-menu")) {
        document.querySelectorAll(".dropdown-menu.show").forEach((dropdown) => {
          dropdown.classList.remove("show")
        })
      }
    })
  }

  // ==========================================================================
  // LOADING STATES
  // ==========================================================================

  setupLoadingStates() {
    // Show loading spinner for AJAX requests
    this.setupAjaxLoading()

    // Page loading animation
    this.setupPageLoading()
  }

  setupAjaxLoading() {
    // Intercept fetch requests to show loading states
    const originalFetch = window.fetch
    window.fetch = (...args) => {
      this.showGlobalLoading()
      return originalFetch(...args)
        .then((response) => {
          this.hideGlobalLoading()
          return response
        })
        .catch((error) => {
          this.hideGlobalLoading()
          throw error
        })
    }
  }

  setupPageLoading() {
    // Hide loading screen when page is fully loaded
    window.addEventListener("load", () => {
      const loadingScreen = document.getElementById("loading-screen")
      if (loadingScreen) {
        setTimeout(() => {
          loadingScreen.style.opacity = "0"
          setTimeout(() => {
            loadingScreen.style.display = "none"
          }, 300)
        }, 500)
      }
    })
  }

  showGlobalLoading() {
    let loader = document.getElementById("global-loader")
    if (!loader) {
      loader = document.createElement("div")
      loader.id = "global-loader"
      loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        z-index: var(--z-toast);
        animation: loading-bar 2s ease-in-out infinite;
      `
      document.body.appendChild(loader)

      // Add loading bar animation
      if (!document.querySelector("#loading-bar-styles")) {
        const style = document.createElement("style")
        style.id = "loading-bar-styles"
        style.textContent = `
          @keyframes loading-bar {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(0%); }
            100% { transform: translateX(100%); }
          }
        `
        document.head.appendChild(style)
      }
    }
    loader.style.display = "block"
  }

  hideGlobalLoading() {
    const loader = document.getElementById("global-loader")
    if (loader) {
      setTimeout(() => {
        loader.style.display = "none"
      }, 300)
    }
  }

  // ==========================================================================
  // NOTIFICATION SYSTEM
  // ==========================================================================

  setupNotifications() {
    // Create notification container
    if (!document.getElementById("notification-container")) {
      const container = document.createElement("div")
      container.id = "notification-container"
      container.style.cssText = `
        position: fixed;
        top: var(--space-4);
        right: var(--space-4);
        z-index: var(--z-toast);
        max-width: 400px;
      `
      document.body.appendChild(container)
    }
  }

  showNotification(message, type = "info", duration = 5000) {
    const container = document.getElementById("notification-container")
    if (!container) return

    const notification = document.createElement("div")
    notification.className = `notification notification-${type}`
    notification.style.cssText = `
      background: white;
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-xl);
      padding: var(--space-4);
      margin-bottom: var(--space-2);
      border-left: 4px solid var(--${type === "success" ? "accent-green" : type === "error" ? "accent-red" : type === "warning" ? "accent-yellow" : "primary-500"});
      animation: slideInRight 0.3s ease-out;
      cursor: pointer;
    `

    notification.innerHTML = `
      <div style="display: flex; align-items: center; gap: var(--space-2);">
        <div style="flex: 1; font-size: var(--text-sm); color: var(--gray-800);">
          ${message}
        </div>
        <button style="background: none; border: none; color: var(--gray-500); cursor: pointer; font-size: var(--text-lg);">
          ×
        </button>
      </div>
    `

    // Close notification on click
    notification.addEventListener("click", () => {
      this.hideNotification(notification)
    })

    container.appendChild(notification)

    // Auto-hide notification
    if (duration > 0) {
      setTimeout(() => {
        this.hideNotification(notification)
      }, duration)
    }
  }

  hideNotification(notification) {
    notification.style.animation = "slideInRight 0.3s ease-out reverse"
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification)
      }
    }, 300)
  }

  // ==========================================================================
  // UTILITY METHODS
  // ==========================================================================

  // Format currency
  formatCurrency(amount, currency = "USD") {
    return new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: currency,
    }).format(amount)
  }

  // Format date
  formatDate(date, options = {}) {
    const defaultOptions = {
      year: "numeric",
      month: "long",
      day: "numeric",
    }
    return new Intl.DateTimeFormat("en-US", { ...defaultOptions, ...options }).format(new Date(date))
  }

  // Debounce function
  debounce(func, wait) {
    let timeout
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout)
        func(...args)
      }
      clearTimeout(timeout)
      timeout = setTimeout(later, wait)
    }
  }

  // Throttle function
  throttle(func, limit) {
    let inThrottle
    return function () {
      const args = arguments
      
      if (!inThrottle) {
        func.apply(this, args)
        inThrottle = true
        setTimeout(() => (inThrottle = false), limit)
      }
    }
  }

  // Local storage helpers
  setStorage(key, value) {
    try {
      localStorage.setItem(key, JSON.stringify(value))
    } catch (e) {
      console.warn("Could not save to localStorage:", e)
    }
  }

  getStorage(key, defaultValue = null) {
    try {
      const item = localStorage.getItem(key)
      return item ? JSON.parse(item) : defaultValue
    } catch (e) {
      console.warn("Could not read from localStorage:", e)
      return defaultValue
    }
  }

  removeStorage(key) {
    try {
      localStorage.removeItem(key)
    } catch (e) {
      console.warn("Could not remove from localStorage:", e)
    }
  }
}

// Initialize the application when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  window.grandeFitnessApp = new GrandeFitnessApp()
})

// Export for module usage
if (typeof module !== "undefined" && module.exports) {
  module.exports = GrandeFitnessApp
}
