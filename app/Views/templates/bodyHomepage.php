<?php
// Tentukan role berdasarkan session
$role = 'user'; // Default jika user belum login

if (isset($_SESSION['id_role'])) {
    if ($_SESSION['id_role'] == 1) {
        $role = 'audiens';   // Audiens
    }
}

// Tentukan base URL berdasarkan role
function getPenampilanUrl($role)
{
    if ($role === 'audiens') {
        return base_url('Audiens/penampilanAudiens/');
    } else {
        return base_url('Audiens/listPenampilan/');
    }
}

function getAudisiUrl($role)
{
    if ($role === 'audiens') {
        return base_url('Audiens/audisiAudiens/');
    } else {
        return base_url('Audiens/listAudisi/');
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">

<body>
    <div class="homepage">

        <!-- Slider Area Start -->
        <section class="slider-section-two rel z-1">
            <div class="main-slider-active">
                <!-- Slide Pertama -->
                <div class="slider-single-item slider-1">
                    <div class="img-container">
                        <img src="<?= base_url('public/assets/images/slider/slider4.jpg') ?>" alt="Slider Image">
                        <div class="overlay"></div>
                    </div>
                    <div class="container">
                        <div class="slide-content">
                            <h1>Penampilan Teater</h1>
                            <p>Semakin mudah dalam mencari informasi terkait
                                pertunjukan teater di wilayah Jabodetabek</p>
                            <div class="slider-btns">
                                <a href="<?= getPenampilanUrl($role) ?>" class="theme-btn">Cek Jadwal</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide Kedua -->
                <div class="slider-single-item slider-2">
                    <div class="img-container">
                        <img src="<?= base_url('public/assets/images/slider/slide2.jpg') ?>" alt="Slider Image">
                        <div class="overlay"></div>
                    </div>
                    <div class="container">
                        <div class="slide-content">
                            <h1>Audisi Teater</h1>
                            <p>Semakin mudah dalam mencari informasi terkait
                                audisi teater di wilayah Jabodetabek</p>
                            <div class="slider-btns wow fadeInUp delay-0-4s">
                                <a href="<?= getAudisiUrl($role) ?>" class="theme-btn">Cek Jadwal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Slider Area end -->

        <!-- About Three area start -->
        <section class="about-three-area rel z-1 pt-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 d-flex">
                        <div class="about-three-images">
                            <div class="row">
                                <div class="col-6">
                                    <img src="<?= base_url('public/assets/images/about/about1.jpg') ?>" alt="About">
                                    <img src="<?= base_url('public/assets/images/about/about2.jpg') ?>" alt="About">
                                </div>
                                <div class="col-6">
                                    <img src="<?= base_url('public/assets/images/about/about3.jpg') ?>" alt="About">
                                    <img src="<?= base_url('public/assets/images/about/about4.jpg') ?>" alt="About">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center">
                        <div class="about-three-content">
                            <div class="section-title">
                                <span class="sub-title style-three">About us</span>
                                <h2>Booking tiket teater untuk pertunjukan dan audisi teater dengan mudah!</h2>
                            </div>
                            <p>Theaterform merupakan gabungan dari dua kata dalam bahasa inggris, yaitu ‘Theater’ dan ‘Perform’. Kami mendukung berkembangnya seni teater dengan menyediakan informasi terkait pertunjukan maupun audisi teater di wilayah Jabodetabek.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- About Three Area end -->

        <!-- FAQ area start -->
        <section class="faq-area-three rel z-1 py-120">
            <div class="container faq-container">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-12 faq-content-part">
                        <div class="section-title">
                            <span class="sub-title">FAQ</span>
                            <h2 class="fw-bold">Apa yang audiens ingin ketahui?</h2>
                        </div>
                        <div class="accordion mt-3" id="faqAccordion">
                            <div class="accordion-item">
                                <h4 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Bagaimana caranya melakukan booking tiket?
                                    </button>
                                </h4>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne">
                                    <div class="accordion-body">
                                        Pengguna perlu terlebih dahulu melakukan login atau registrasi akun untuk memilih detail pertunjukan yang diinginkan. Lalu, pilih pesan tiket.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h4 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Apa benefit yang didapatkan komunitas teater?
                                    </button>
                                </h4>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                                    <div class="accordion-body">
                                        <ul class="list-style-two">
                                            <li>Bukti pembayaran tiket berbayar yang diunggah audiens dan aktor.</li>
                                            <li>List pertunjukan teater berdasarkan 'Sedang Tayang' dan 'Akan Tayang'.</li>
                                            <li>Kategori audisi untuk aktor dan staff.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h4 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Dimana saya bisa menghubungi admin Theaterform?
                                    </button>
                                </h4>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
                                    <div class="accordion-body">
                                        User bisa memilih sosial media yang ada di footer halaman.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 faq-three-image">
                        <img src="<?= base_url('public/assets/images/faq/faq1.jpg') ?>" alt="FAQ">
                    </div>
                </div>
            </div>
        </section>
        <!-- FAQ Area end -->

    </div>
</body>

</html>