<?php
$ref = $_GET["ref"] ?? null;

if (!$ref) {
  echo "<div class='alert alert-danger text-center'>Referencia no válida.</div>";
  return;
}
?>

<div class="container py-5">
  <div class="text-center">
    <h2>Confirmar pago con PayPhone</h2>
    <p>Verifica los detalles de tu pedido y procede con el pago seguro.</p>

    <div id="pp-button" class="mt-4"></div>
  </div>
</div>

<!-- Scripts PayPhone -->
<script src="https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.js" type="module"></script>
<link href="https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.css" rel="stylesheet">

<script type="module">
  document.addEventListener("DOMContentLoaded", () => {
    const ref = localStorage.getItem("pp_order_ref");
    const amount = parseFloat(localStorage.getItem("pp_total")) * 100;

    if (!ref || !amount) {
      alert("No se encontró información de la orden.");
      window.location.href = "/";
      return;
    }

    new PPaymentButtonBox({
      token: "<?= $_ENV['PAYPHONE_API_KEY'] ?>",
      storeId: "<?= $_ENV['PAYPHONE_STORE_ID'] ?>",
      clientTransactionId: ref,
      amount: amount,
      amountWithoutTax: amount,
      amountWithTax: 0,
      tax: 0,
      currency: "USD",
      reference: "Proyecto Ecuador",
      callback: (transaction) => {
        if (transaction.status === "approved") {
          window.location.href = `/payphone/finalizar.php?ref=${ref}&transactionId=${transaction.transactionId}`;
        } else {
          alert("⚠️ Pago no completado.");
        }
      }
    }).render("pp-button");
  });
</script>
