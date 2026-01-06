// Professional Grande Fitness Animations and Interactions

const $ = window.jQuery // Declare the $ variable

$(document).ready(() => {
  // Page Loader
  setTimeout(() => {
    $("#pageLoader").addClass("fade-out")
    setTimeout(() => {
      $("#pageLoader").remove()
    }, 500)
  }, 1500)

  // Navbar scroll effect
  $(window).scroll(() => {
    if ($(window).scrollTop() > 50) {
      $("#mainNavbar").addClass("scrolled")
    } else {
      $("#mainNavbar").removeClass("scrolled")
    }
  })

  // Smooth scrolling for navigation links
  $('a[href^="#"]').on("click", function (event) {
    var target = $(this.getAttribute("href"))
    if (target.length) {
      event.preventDefault()
      $("html, body")
        .stop()
        .animate(
          {
            scrollTop: target.offset().top - 80,
          },
          1000,
          "easeInOutExpo",
        )
    }
  })

  // Update active nav link on scroll
  $(window)
    .scroll(() => {
      var scrollDistance = $(window).scrollTop()

      $("section[id]").each(function (i) {
        if ($(this).position().top <= scrollDistance + 100) {
          $(".nav-link.active").removeClass("active")
          $(".nav-link").eq(i).addClass("active")
        }
      })
    })
    .scroll()

  // Fade in animation on scroll
  function fadeInOnScroll() {
    $(".fade-in").each(function () {
      var elementTop = $(this).offset().top
      var elementBottom = elementTop + $(this).outerHeight()
      var viewportTop = $(window).scrollTop()
      var viewportBottom = viewportTop + $(window).height()

      if (elementBottom > viewportTop && elementTop < viewportBottom) {
        $(this).addClass("visible")
      }
    })
  }

  // Initial check for elements in viewport
  fadeInOnScroll()

  // Check on scroll
  $(window).scroll(fadeInOnScroll)

  // Service card hover effects
  $(".service-card").hover(
    function () {
      $(this).find("img").css("transform", "scale(1.1)")
    },
    function () {
      $(this).find("img").css("transform", "scale(1)")
    },
  )

  // Trainer card animations
  $(".trainer-card").hover(
    function () {
      $(this).find(".trainer-image").css("transform", "scale(1.1) rotate(5deg)")
    },
    function () {
      $(this).find(".trainer-image").css("transform", "scale(1) rotate(0deg)")
    },
  )

  // Button click animations
  $(".btn-primary-custom, .btn-outline-custom").on("click", function (e) {
    var ripple = $('<span class="ripple"></span>')
    var btnOffset = $(this).offset()
    var xPos = e.pageX - btnOffset.left
    var yPos = e.pageY - btnOffset.top

    ripple.css({
      position: "absolute",
      top: yPos + "px",
      left: xPos + "px",
      width: "0",
      height: "0",
      borderRadius: "50%",
      background: "rgba(255, 255, 255, 0.5)",
      transform: "translate(-50%, -50%)",
      animation: "ripple-effect 0.6s linear",
    })

    $(this).append(ripple)

    setTimeout(() => {
      ripple.remove()
    }, 600)
  })

  // Parallax effect for hero section
  $(window).scroll(() => {
    var scrolled = $(window).scrollTop()
    var parallax = $(".hero-section")
    var speed = scrolled * 0.5

    parallax.css("background-position", "center " + speed + "px")
  })

  // Counter animation for statistics (if you want to add stats)
  function animateCounter(element, target) {
    $({ countNum: 0 }).animate(
      {
        countNum: target,
      },
      {
        duration: 2000,
        easing: "linear",
        step: function () {
          element.text(Math.floor(this.countNum))
        },
        complete: () => {
          element.text(target)
        },
      },
    )
  }

  // Typing effect for hero subtitle
  function typeWriter(element, text, speed = 100) {
    let i = 0
    element.text("")

    function type() {
      if (i < text.length) {
        element.text(element.text() + text.charAt(i))
        i++
        setTimeout(type, speed)
      }
    }

    setTimeout(type, 1000)
  }

  // Initialize typing effect
  const heroSubtitle = $(".hero-subtitle")
  const originalText = heroSubtitle.text()
  typeWriter(heroSubtitle, originalText, 50)

  // Mobile menu close on link click
  $(".navbar-nav .nav-link").on("click", () => {
    if ($(window).width() < 992) {
      $(".navbar-collapse").collapse("hide")
    }
  })

  // Add loading states to buttons
  $(".btn-primary-custom, .btn-outline-custom").on("click", function () {
    var btn = $(this)
    var originalText = btn.text()

    btn.prop("disabled", true)
    btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...')

    setTimeout(() => {
      btn.prop("disabled", false)
      btn.text(originalText)
    }, 2000)
  })

  // Intersection Observer for better performance (modern browsers)
  if ("IntersectionObserver" in window) {
    const observerOptions = {
      threshold: 0.1,
      rootMargin: "0px 0px -50px 0px",
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible")
        }
      })
    }, observerOptions)

    document.querySelectorAll(".fade-in").forEach((el) => {
      observer.observe(el)
    })
  }
})

// Add CSS for ripple effect
const style = document.createElement("style")
style.textContent = `
    @keyframes ripple-effect {
        to {
            width: 200px;
            height: 200px;
            opacity: 0;
        }
    }
    
    .btn-primary-custom, .btn-outline-custom {
        position: relative;
        overflow: hidden;
    }
`
document.head.appendChild(style)

// Smooth scrolling polyfill for older browsers
if (!window.CSS || !CSS.supports("scroll-behavior", "smooth")) {
  $('a[href^="#"]').on("click", function (event) {
    var target = $(this.getAttribute("href"))
    if (target.length) {
      event.preventDefault()
      $("html, body").animate(
        {
          scrollTop: target.offset().top - 80,
        },
        1000,
      )
    }
  })
}
