<div id="header-slider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#header-slider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#header-slider" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#header-slider" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#header-slider" data-bs-slide-to="3" aria-label="Slide 4"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="slider1.png" class="d-block w-100" alt="First Slide">
            <div class="carousel-caption d-none d-md-block">
                <h3>Welcome to EmpowerHUB</h3>
                <p>Discover amazing deals and offers with out.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="slider4.png" class="d-block w-100" alt="Fourth Slide">
        </div>
        <div class="carousel-item">
            <img src="slider2.png" class="d-block w-100" alt="Second Slide">
        </div>
        <div class="carousel-item">
            <a href="signup.php"><img src="slider3.png" class="d-block w-100" alt="Third Slide"></a>
        </div>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#header-slider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#header-slider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<style>
    #header-slider .carousel-item img {
        height: 729px;
        
    }

    #header-slider .carousel-caption {
        background-color: rgba(0, 0, 0, 0.5);
        padding: 15px;
        border-radius: 10px;
    }
</style>
