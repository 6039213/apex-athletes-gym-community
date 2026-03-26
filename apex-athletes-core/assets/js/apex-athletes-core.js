// Apex Athletes Core – front-end logic
(function () {
  function round(value) {
    return Math.round(value);
  }

  function attachCalculator() {
    var form = document.getElementById("apex-calculator-form");
    if (!form) return;

    var ageInput = document.getElementById("apex-age");
    var genderInput = document.getElementById("apex-gender");
    var weightInput = document.getElementById("apex-weight");
    var heightInput = document.getElementById("apex-height");
    var activityInput = document.getElementById("apex-activity");
    var goalInput = document.getElementById("apex-goal");

    var maintenanceEl = document.getElementById("apex-maintenance");
    var goalCaloriesEl = document.getElementById("apex-goal-calories");
    var proteinEl = document.getElementById("apex-protein");
    var fatsEl = document.getElementById("apex-fats");
    var carbsEl = document.getElementById("apex-carbs");

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      var age = parseFloat(ageInput.value);
      var gender = genderInput.value;
      var weight = parseFloat(weightInput.value);
      var height = parseFloat(heightInput.value);
      var activity = parseFloat(activityInput.value);
      var goal = goalInput.value;

      if (
        !age ||
        !weight ||
        !height ||
        !activity ||
        age <= 0 ||
        weight <= 0 ||
        height <= 0
      ) {
        alert("Please fill in all fields with valid numbers.");
        return;
      }

      // Mifflin-St Jeor equation
      var bmr;
      if (gender === "female") {
        bmr = 10 * weight + 6.25 * height - 5 * age - 161;
      } else {
        bmr = 10 * weight + 6.25 * height - 5 * age + 5;
      }

      var maintenance = bmr * activity;
      var goalCalories = maintenance;

      if (goal === "cut") {
        goalCalories = maintenance * 0.8; // 20% deficit
      } else if (goal === "bulk") {
        goalCalories = maintenance * 1.15; // 15% surplus
      }

      // Macro logic (grams per kg bodyweight)
      var proteinPerKg;
      var fatPerKg;

      if (goal === "cut") {
        proteinPerKg = 2.2;
        fatPerKg = 0.7;
      } else if (goal === "bulk") {
        proteinPerKg = 1.6;
        fatPerKg = 0.9;
      } else {
        // maintenance
        proteinPerKg = 1.8;
        fatPerKg = 0.8;
      }

      var proteinGrams = proteinPerKg * weight;
      var fatGrams = fatPerKg * weight;

      var proteinCalories = proteinGrams * 4;
      var fatCalories = fatGrams * 9;

      var remainingCalories = goalCalories - (proteinCalories + fatCalories);
      if (remainingCalories < 0) {
        remainingCalories = 0;
      }

      var carbGrams = remainingCalories / 4;

      maintenanceEl.textContent = round(maintenance) + " kcal";
      goalCaloriesEl.textContent = round(goalCalories) + " kcal";

      proteinEl.textContent = round(proteinGrams) + " g";
      fatsEl.textContent = round(fatGrams) + " g";
      carbsEl.textContent = round(carbGrams) + " g";
    });
  }

  function attachAppNavigation() {
    var app = document.querySelector(".apex-app");
    if (!app) return;

    var navButtons = app.querySelectorAll("[data-apex-section]");
    var sections = app.querySelectorAll("[data-apex-view]");

    function activate(target) {
      sections.forEach(function (section) {
        if (section.getAttribute("data-apex-view") === target) {
          section.classList.add("apex-app__section--active");
        } else {
          section.classList.remove("apex-app__section--active");
        }
      });

      navButtons.forEach(function (btn) {
        if (btn.getAttribute("data-apex-section") === target) {
          btn.classList.add("apex-app__nav-btn--active");
        } else {
          btn.classList.remove("apex-app__nav-btn--active");
        }
      });
    }

    navButtons.forEach(function (btn) {
      btn.addEventListener("click", function (e) {
        e.preventDefault();
        var target = btn.getAttribute("data-apex-section");
        if (target) {
          activate(target);
        }
      });
    });

    // default view
    activate("home");
  }

  function init() {
    attachCalculator();
    attachAppNavigation();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();

