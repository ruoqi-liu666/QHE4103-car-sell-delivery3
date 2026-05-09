document.addEventListener("DOMContentLoaded", () => {
  setupSellerForm();
  setupCarForm();
});

function setupSellerForm() {
  const form = document.getElementById("sellerForm");
  if (!form) return;

  form.addEventListener("submit", (event) => {
    clearErrors(form);
    const data = Object.fromEntries(new FormData(form).entries());
    const rules = {
      name: [/^[A-Za-z\s]+$/.test(data.name.trim()), "Name can contain letters and spaces only."],
      address: [/^[A-Za-z0-9\s,.-]+$/.test(data.address.trim()), "Address contains unsupported characters."],
      phone: [/^1[3-9]\d{9}$/.test(data.phone.trim()), "Phone number must be a valid China mobile number."],
      email: [/^[^@\s]+@[^@\s]+\.(cn|com)$/i.test(data.email.trim()), "Email must end with .cn or .com."],
      username: [/^[A-Za-z0-9]{6,}$/.test(data.username.trim()), "Username must be at least 6 alphanumeric characters."],
      password: [/^[A-Za-z0-9]{6,}$/.test(data.password.trim()), "Password must be at least 6 alphanumeric characters."]
    };

    if (!applyRules(form, rules)) {
      event.preventDefault();
      setStatus("Please correct the highlighted fields before submitting.", false);
    }
  });
}

function setupCarForm() {
  const form = document.getElementById("carForm");
  if (!form) return;

  form.addEventListener("submit", (event) => {
    clearErrors(form);
    const data = Object.fromEntries(new FormData(form).entries());
    const nextYear = new Date().getFullYear() + 1;
    const rules = {
      seller_id: [Number(data.seller_id) > 0, "Please choose a registered seller."],
      make: [/^[A-Za-z0-9\s-]{2,60}$/.test(data.make.trim()), "Brand must be 2-60 valid characters."],
      model: [/^[A-Za-z0-9\s.-]{1,80}$/.test(data.model.trim()), "Model contains unsupported characters."],
      manufacture_year: [Number(data.manufacture_year) >= 1980 && Number(data.manufacture_year) <= nextYear, "Enter a valid manufacture year."],
      price: [Number(data.price) > 0, "Price must be greater than 0."],
      mileage: [Number(data.mileage) >= 0 && Number(data.mileage) <= 2000000, "Mileage must be between 0 and 2,000,000."],
      color: [/^[A-Za-z\s-]{2,40}$/.test(data.color.trim()), "Color contains unsupported characters."],
      fuel_type: [["Petrol", "Diesel", "Hybrid", "Electric"].includes(data.fuel_type), "Please choose a valid fuel type."],
      transmission: [["Automatic", "Manual"].includes(data.transmission), "Please choose a valid transmission."],
      location: [/^[A-Za-z0-9\s,.-]{2,120}$/.test(data.location.trim()), "Location contains unsupported characters."],
      description: [data.description.trim().length >= 20 && data.description.trim().length <= 1000, "Description must be 20-1000 characters."]
    };

    if (!applyRules(form, rules)) {
      event.preventDefault();
      setStatus("Please correct the highlighted fields before submitting.", false);
    }
  });
}

function applyRules(form, rules) {
  let valid = true;

  Object.entries(rules).forEach(([name, rule]) => {
    const [passes, message] = rule;
    if (passes) return;

    setFieldError(form.elements[name], message);
    valid = false;
  });

  return valid;
}

function clearErrors(form) {
  form.querySelectorAll(".field").forEach((field) => field.classList.remove("is-invalid"));
  form.querySelectorAll(".error-message").forEach((item) => {
    item.textContent = "";
  });
  setStatus("", true);
}

function setFieldError(input, message) {
  if (!input) return;

  const wrapper = input.closest(".field");
  if (!wrapper) return;

  wrapper.classList.add("is-invalid");
  const messageNode = wrapper.querySelector(".error-message");
  if (messageNode) {
    messageNode.textContent = message;
  }
}

function setStatus(message, success) {
  const node = document.getElementById("formStatus");
  if (!node) return;

  node.textContent = message;
  node.classList.toggle("is-success", success);
  node.classList.toggle("is-error", !success);
}

