<!DOCTYPE html>
<html lang="zxx">

<body>
    <div class="homepage">

        <!-- Slider Area Start -->
        <section class="slider-section-two rel z-1">
            <div class="main-slider-active">
                <!-- Slide Pertama -->
                <div class="slider-single-item slider-1">
                    <img src="<?= base_url('public/assets/images/slider/slider4.jpg') ?>" alt="Slider Image">
                    <div class="container">
                        <div class="slide-content">
                            <h1>Penampilan Teater</h1>
                            <p>Semakin mudah dalam mencari informasi terkait
                                pertunjukan teater di wilayah Jabodetabek</p>
                            <div class="slider-btns">
                                <a href="<?= base_url('MitraTeater/listPenampilan') ?>" class="theme-btn">Cek Jadwal</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide Kedua -->
                <div class="slider-single-item slider-2">
                    <img src="<?= base_url('public/assets/images/slider/slide2.jpg') ?>" alt="Slider Image">
                    <div class="container">
                        <div class="slide-content">
                            <h1>Audisi Teater</h1>
                            <p>Semakin mudah dalam mencari informasi terkait
                                audisi teater di wilayah Jabodetabek</p>
                            <div class="slider-btns wow fadeInUp delay-0-4s">
                                <a href="<?= base_url('MitraTeater/listAudisi') ?>" class="theme-btn">Cek Jadwal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Slider Area end -->

        <div class="main-content">
            <div class="page-content">
                <div class="row">
                    <div class="col-xxl-9">
                        <div class="row">
                            <div class="col-xl-4">
                                <a href="<?= base_url('MitraTeater/crudPenampilan') ?>" class="card bg-success-subtle text-decoration-none">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="avatar avatar-label-danger">
                                                <i class="fa-regular fa-chess-knight"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="text-success mb-1">Pengaturan</p>
                                                <h5 class="mb-0">Penampilan Teater</h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4">
                                <a href="<?= base_url('MitraTeater/crudAudisi') ?>" class="card bg-warning-subtle text-decoration-none">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="avatar avatar-label-success">
                                                <i class="fa-regular fa-chess-bishop"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="text-warning mb-1">Pengaturan</p>
                                                <h5 class="mb-0">Audisi Teater</h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>