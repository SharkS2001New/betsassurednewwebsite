(function () {
  var cfg = window.BA_PAY || {};
  var form = document.getElementById("pay-mpesa-form");
  if (!form) return;

  var alertEl = document.getElementById("pay-alert");
  var submitBtn = document.getElementById("pay-submit");
  var phoneInput = document.getElementById("phone_number");

  function showAlert(type, message) {
    if (!alertEl) return;
    alertEl.hidden = false;
    alertEl.className = "auth-alert " + (type === "success" ? "auth-alert-success" : "auth-alert-error");
    alertEl.textContent = message;
  }

  function api(path, options) {
    options = options || {};
    return fetch(path, {
      method: options.method || "GET",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: options.body ? JSON.stringify(options.body) : undefined,
      credentials: "same-origin",
    }).then(function (res) {
      return res.json().then(function (data) {
        return { ok: res.ok, status: res.status, data: data };
      });
    });
  }

  function sleep(ms) {
    return new Promise(function (resolve) {
      setTimeout(resolve, ms);
    });
  }

  function pollStatus(checkoutRequestId, paymentId) {
    var started = Date.now();
    var timeoutMs = 90000;
    function tick() {
      var qs = [];
      if (checkoutRequestId) qs.push("checkout_request_id=" + encodeURIComponent(checkoutRequestId));
      if (paymentId) qs.push("payment_id=" + encodeURIComponent(paymentId));
      return api("/api/pay/mpesa/status?" + qs.join("&")).then(function (result) {
        var label = result.data && result.data.data && result.data.data.status_label;
        if (label === "paid" || label === "failed") {
          return result;
        }
        if (Date.now() - started >= timeoutMs) {
          return result;
        }
        showAlert("success", "Still waiting for your M-Pesa PIN confirmation…");
        return sleep(3000).then(tick);
      });
    }
    return tick();
  }

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    var phone = String(phoneInput.value || "").replace(/\D/g, "");
    if (!/^[17]\d{8}$/.test(phone)) {
      showAlert("error", "Enter a valid M-Pesa number like 7XXXXXXXX.");
      return;
    }
    if (!cfg.planIds || !cfg.planIds.length) {
      showAlert("error", "No plan selected.");
      return;
    }

    submitBtn.disabled = true;
    submitBtn.textContent = "Sending STK…";
    showAlert("success", "Check your phone and enter your M-Pesa PIN.");

    api("/api/pay/mpesa/stk-push", {
      method: "POST",
      body: {
        plan_ids: cfg.planIds,
        phone_number: "0" + phone,
        site: "bets",
      },
    })
      .then(function (stk) {
        if (!stk.ok || !stk.data || !stk.data.success) {
          throw new Error((stk.data && stk.data.message) || "Could not start M-Pesa payment.");
        }
        var checkoutRequestId = stk.data.data && stk.data.data.checkout_request_id;
        var paymentId = stk.data.data && stk.data.data.payment_id;
        showAlert(
          "success",
          (stk.data.data && stk.data.data.customer_message) ||
            "Check your phone on 0" + phone + " and enter your M-Pesa PIN."
        );
        submitBtn.textContent = "Waiting for payment…";
        return pollStatus(checkoutRequestId, paymentId);
      })
      .then(function (settled) {
        var data = settled.data && settled.data.data ? settled.data.data : {};
        if (data.status_label === "paid") {
          showAlert("success", "Payment confirmed. Unlocking your tips…");
          var redirect = data.redirect_path || "/dashboard";
          window.location.href = "/pay/mpesa/complete?redirect=" + encodeURIComponent(redirect);
          return;
        }
        if (data.status_label === "failed") {
          throw new Error(data.result_desc || "Payment failed. Please try again.");
        }
        throw new Error("Payment still pending. If you paid, wait a moment then open your dashboard.");
      })
      .catch(function (err) {
        showAlert("error", err.message || "Payment failed.");
        submitBtn.disabled = false;
        submitBtn.textContent = "Pay again";
      });
  });
})();
