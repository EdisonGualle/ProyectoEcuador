<video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
    <source src="views/assets/video/bg.mp4" type="video/mp4" />
</video>



<div class="masthead">



    <div class="masthead-content text-white">

        <div class="container">
            <div class="h3 p-3 mt-1 mb-0">
                <a href="/" class="text-white d-flex justify-content-center">
                    <img src="/views/assets/img/ProyectoEcuadorLogo.png" alt="Proyecto Ecuador"
                        style="height: 100px; object-fit: contain; filter: drop-shadow(0 0 3px rgba(0,0,0,0.7));">
                </a>
            </div>
        </div>

        <div class="container-fluid px-4 px-lg-0">

            <h1 class="fst-italic lh-1 mb-4">Nuestro próximo sorteo pronto estará disponible</h1>
            <p class="mb-5">Estamos trabajando duro para lanzar nuestro próximo sorteo. ¡Regístrese a continuación para
                recibir actualizaciones y recibir notificaciones cuando lo lancemos!</p>

            <form>

                <div class="row input-group-newsletter">
                    <div class="col-12 col-lg-6">
                        <input type="email" class="form-control rounded-end py-3" placeholder="Correo Electrónico"
                            onchange="validateJS(event,'email')" name="email" required>

                        <div class="invalid-feedback">Por favor llena este campo correctamente.</div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <button class="btn btn-default b1 w-100 mt-2 mt-lg-0 border-0"
                            type="submit">¡Notificarme!</button>
                    </div>
                </div>

            </form>

        </div>

    </div>

</div>

<div class="social-icons">
    <div class="d-flex flex-row flex-lg-column justify-content-center align-items-center h-100 mt-3 mt-lg-0">

        <a class="btn btn-dark m-3" href="<?= $_ENV['SOCIAL_FACEBOOK'] ?>" target="_blank" rel="noopener noreferrer">
            <i class="bi bi-facebook"></i>
        </a>

        <a class="btn btn-dark m-3" href="<?= $_ENV['SOCIAL_INSTAGRAM'] ?>" target="_blank" rel="noopener noreferrer">
            <i class="bi bi-instagram"></i>
        </a>

        <a class="btn btn-dark m-3" href="<?= $_ENV['SOCIAL_TIKTOK'] ?>" target="_blank" rel="noopener noreferrer">
            <i class="bi bi-tiktok"></i>
        </a>

    </div>
</div>