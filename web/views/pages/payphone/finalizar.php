<?php
$id = $_GET["id"] ?? null;
$ref = $_GET["clientTransactionId"] ?? null;

if (!$id || !$ref) {
    echo "<div class='alert alert-danger text-center'>❌ Faltan datos para verificar el pago.</div>";
    return;
}
?>

<!-- Canvas para el confeti -->
<canvas id="confetti-canvas" style="position: fixed; top: 0; left: 0; pointer-events: none; display: none; z-index: 9999;"></canvas>

<div class="container text-center py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Animación de carga -->
            <div id="loadingAnimation" class="mb-4">
                <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <h3 class="mt-3">Verificando tu pago con PayPhone...</h3>
                <p class="text-muted">Por favor, no cierres esta ventana.</p>
            </div>

            <!-- Éxito -->
            <div id="successMessage" class="d-none">
                <div class="checkmark-animation mb-4">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
                <h3 class="text-success">¡Pago confirmado!</h3>
                <p class="lead">Tu participación en el sorteo ha sido registrada correctamente.</p>
                <div class="alert alert-success mt-3">
                    <i class="fas fa-ticket-alt me-2"></i>
                    Estamos preparando tus números de la suerte...
                </div>
            </div>

            <!-- Error -->
            <div id="errorMessage" class="d-none">
                <div class="error-animation mb-4">
                    <i class="fas fa-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-danger">¡Oops! Algo salió mal</h3>
                <p class="lead" id="errorText">Hubo un problema al procesar tu pago.</p>
                <a href="/checkout" class="btn btn-danger mt-3">
                    <i class="fas fa-arrow-left me-2"></i>Volver a intentar
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const loading = document.getElementById("loadingAnimation");
    const success = document.getElementById("successMessage");
    const error = document.getElementById("errorMessage");
    const errorText = document.getElementById("errorText");
    const confettiCanvas = document.getElementById("confetti-canvas");

    try {
        const response = await fetch("/controllers/payphone.controller.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                id: "<?php echo $id ?>",
                ref: "<?php echo $ref ?>"
            })
        });

        const data = await response.json();

        loading.classList.add("d-none");

        if (data.status === "success") {
            success.classList.remove("d-none");

            // Activar confeti personalizado
            startConfetti();

            setTimeout(() => {
                window.location.href = "/thanks?ref=<?php echo $ref ?>";
            }, 3000);
        } else {
            errorText.textContent = data.message || "Hubo un problema al procesar tu pago.";
            error.classList.remove("d-none");
        }

    } catch (err) {
        loading.classList.add("d-none");
        errorText.textContent = "Error de conexión con el servidor. Por favor intenta nuevamente.";
        error.classList.remove("d-none");
    }

    // Confeti personalizado
    function startConfetti() {
        confettiCanvas.style.display = "block";
        const canvas = confettiCanvas;
        const ctx = canvas.getContext("2d");
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const pieces = [];
        const colors = ['#6c5ce7', '#a29bfe', '#00b894', '#55efc4', '#fd79a8', '#e17055', '#fdcb6e', '#ffeaa7'];

        for (let i = 0; i < 150; i++) {
            pieces.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height - canvas.height,
                size: Math.random() * 10 + 5,
                color: colors[Math.floor(Math.random() * colors.length)],
                speed: Math.random() * 3 + 2,
                sway: Math.random() * 2 - 1
            });
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            pieces.forEach(piece => {
                ctx.fillStyle = piece.color;
                ctx.fillRect(piece.x, piece.y, piece.size, piece.size);

                piece.y += piece.speed;
                piece.x += piece.sway;

                if (piece.y > canvas.height) {
                    piece.y = -piece.size;
                    piece.x = Math.random() * canvas.width;
                }
            });

            requestAnimationFrame(animate);
        }

        animate();
    }
});
</script>

<style>
.checkmark-animation {
    width: 100px;
    height: 100px;
    margin: 0 auto;
}
.checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke: #28a745;
    fill: none;
    animation: stroke 0.6s ease forwards;
}
.checkmark__check {
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    stroke-width: 2;
    stroke: #28a745;
    fill: none;
    animation: stroke 0.3s ease 0.6s forwards;
}
@keyframes stroke {
    100% {
        stroke-dashoffset: 0;
    }
}
</style>
