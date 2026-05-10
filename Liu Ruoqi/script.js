document.addEventListener("DOMContentLoaded", () => {
  setupRevealAnimations();
  setupRegistrationForm();
});

function setupRegistrationForm() {
  const form = document.getElementById("registrationForm");
  const status = document.getElementById("registrationStatus");
  if (!form || !status) return;

  form.addEventListener("submit", (event) => {
    resetStatus(status);

    const formData = new FormData(form);
    const values = Object.fromEntries(formData.entries());
    const rules = {
      name: {
        valid: /^[A-Za-z\s]+$/.test(values.name.trim()),
        message: "Name can contain letters and spaces only."
      },
      address: {
        valid: /^[A-Za-z0-9\s]+$/.test(values.address.trim()),
        message: "Address can contain letters, numbers, and spaces only."
      },
      phone: {
        valid: /^1[3-9]\d{9}$/.test(values.phone.trim()),
        message: "Phone number must be a valid China mobile number."
      },
      email: {
        valid: /^[^@\s]+@[^@\s]+\.(cn|com)$/i.test(values.email.trim()),
        message: "Email must contain exactly one @ and end with .cn or .com."
      },
      username: {
        valid: /^[A-Za-z0-9]{6,}$/.test(values.username.trim()),
        message: "Username must be at least 6 alphanumeric characters."
      },
      password: {
        valid: /^[A-Za-z0-9]{6,}$/.test(values.password.trim()),
        message: "Password must be at least 6 alphanumeric characters."
      }
    };

    if (!applyValidation(form, rules)) {
      event.preventDefault();
      setStatus(status, "Please correct the highlighted fields before submitting.", false);
    }
  });
}

function applyValidation(form, rules) {
  let valid = true;
  clearErrors(form);

  Object.entries(rules).forEach(([name, rule]) => {
    const field = form.elements[name];
    if (!rule.valid) {
      setFieldError(field, rule.message);
      valid = false;
    }
  });

  return valid;
}

function clearErrors(form) {
  form.querySelectorAll(".field").forEach((field) => field.classList.remove("is-invalid"));
  form.querySelectorAll(".error-message").forEach((item) => {
    item.textContent = "";
  });
}

function setFieldError(input, message) {
  const wrapper = input.closest(".field");
  if (!wrapper) return;
  wrapper.classList.add("is-invalid");
  const messageNode = wrapper.querySelector(".error-message");
  if (messageNode) messageNode.textContent = message;
}

function setStatus(node, message, success) {
  node.textContent = message;
  node.classList.toggle("is-success", success);
  node.classList.toggle("is-error", !success);
}

function resetStatus(node) {
  node.textContent = "";
  node.classList.remove("is-success", "is-error");
}

function setupRevealAnimations() {
  const items = Array.from(document.querySelectorAll(".reveal"));
  if (!items.length) return;

  if (prefersReducedMotion() || typeof IntersectionObserver !== "function") {
    items.forEach((item) => item.classList.add("is-visible"));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      });
    },
    {
      threshold: 0.16,
      rootMargin: "0px 0px -10% 0px"
    }
  );

  items.forEach((item) => observer.observe(item));
}

function prefersReducedMotion() {
  return typeof window.matchMedia === "function" && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
}
