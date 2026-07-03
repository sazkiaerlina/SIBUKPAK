<section
    id="profil"
    class="py-16 px-6"
    x-data="{ menu: 'profil' }">

<div class="max-w-7xl mx-auto">

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-lg p-8 lg:p-12">

            <!-- Logo -->
                <div style="
                        width:400px;
                        position:relative;
                        top:-20px;
                        left: -15px;">

                        <img
                            src="{{ asset('assets/images/bps-logo-hitam.png') }}"
                            alt="Logo BPS">

                 </div>


            <div class="grid lg:grid-cols-4 gap-8">

                <!-- Sidebar -->
                <div class="space-y-4">

                    <button
                    @click="menu='profil'"
                    :class="menu=='profil'
                        ? 'w-full bg-blue-900 text-white rounded-xl px-5 py-4 flex items-center gap-3 font-semibold'
                        : 'w-full bg-blue-100 hover:bg-blue-200 rounded-xl px-5 py-4 flex items-center gap-3'">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6v6l4 2"/>
                        </svg>

                        Profil

                    </button>

                    <button

    @click="menu='tugas'"
    :class="menu=='tugas'
        ? 'w-full bg-blue-900 text-white rounded-xl px-5 py-4 flex items-center gap-3 font-semibold'
        : 'w-full bg-blue-100 hover:bg-blue-200 rounded-xl px-5 py-4 flex items-center gap-3'">


                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12h6m-6 4h6M8 6h8"/>
                        </svg>

                        Tugas & Fungsi

                    </button>

                    <button

    @click="menu='visi'"
    :class="menu=='visi'
        ? 'w-full bg-blue-900 text-white rounded-xl px-5 py-4 flex items-center gap-3 font-semibold'
        : 'w-full bg-blue-100 hover:bg-blue-200 rounded-xl px-5 py-4 flex items-center gap-3'">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"/>
                        </svg>

                        Visi & Misi

                    </button>

                </div>

                <!-- Content -->
                <!-- Content -->
<div class="lg:col-span-3">

    <!-- ================= PROFIL ================= -->
    <div x-show="menu=='profil'">

        <h2 class="text-3xl font-bold text-blue-900 mb-6">
            Profil BPS
        </h2>

        <p class="text-gray-700 leading-8 text-justify">
            Badan Pusat Statistik (BPS) merupakan Lembaga Pemerintah Non-Kementerian 
            yang bertanggung jawab langsung kepada Presiden serta berperan sebagai 
            lembaga statistik yang independen dan tepercaya. 
            BPS bertugas menyediakan data statistik berkualitas sebagai dasar perencanaan, 
            pengambilan keputusan, dan evaluasi pembangunan guna mendukung perumusan kebijakan 
            berbasis data menuju Indonesia Emas 2045.
        </p>
    
    </div>


    <!-- ================= TUGAS ================= -->
    <div x-show="menu=='tugas'">

        <h2 class="text-3xl font-bold text-blue-900 mb-6">
            Tugas & Fungsi
        </h2>

         <h4 class="text-2xl font-bold mt-6 mb-2">
            Tugas
        </h4>

        <p class="text-gray-700 leading-8 text-justify">
              BPS mempunyai tugas melaksanakan tugas pemerintahan 
              di bidang kegiatan statistik sesuai dengan ketentuan peraturan perundang-undangan.
        </p>

         <h4 class="text-2xl font-bold mt-6 mb-2">
            Fungsi
        </h4>

        <ul class="list-disc pl-6 mt-2 space-y-2 text-gray-700">
            Dalam melaksanakan tugas, BPS menyelenggarakan fungsi:
            <li>Pengkajian, penyusunan, dan perumusan kebijakan di bidang statistik;</li>
            <li>Pengoordinasian kegiatan statistik nasional dan regional;</li>
            <li>Penetapan dan penyelenggaraan statistik dasar;</li>
            <li>Penetapan sistem statistik nasional;</li>
            <li>Pembinaan dan fasilitasi terhadap kegiatan instansi pemerintah 
                di bidang kegiatan statistik; dan</li>
            <li>Penyelenggaraan pembinaan dan pelayanan administrasi umum 
                di bidang perencanaan umum, ketatausahaan, organisasi, tata laksana, 
                kepegawaian,keuangan, kearsipan, kehumasan, perlengkapan, dan rumah tangga.</li>
        </ul>

    </div>


    <!-- ================= VISI ================= -->
    <div x-show="menu=='visi'">

        <h2 class="text-3xl font-bold text-blue-900 mb-6">
            Visi & Misi
        </h2>

        <h4 class="text-2xl font-bold mt-6 mb-2">
            Visi
        </h4>

        <p class="text-gray-700 leading-8 text-justify font-bold">
            “Lembaga yang Independen, Tepercaya, 
            dan Berperan Aktif dalam Mendukung Perumusan Kebijakan Berbasis Data Bersama Indonesia Maju Menuju Indonesia Emas 2045”.
        </p>

        <h4 class="text-2xl font-bold mt-6 mb-2">
            Misi
        </h4>

        <ul class="list-disc pl-6 space-y-2 text-gray-700">
            Misi BPS dirumuskan dengan memperhatikan fungsi dan kewenangan BPS. Selaras dengan arah kebijakan di dalam RPJPN 2025-2045, RPJMN 2025-2029, serta Visi Presiden dan Wakil Presiden 2024-2029 yaitu “Bersama Indonesia Maju Menuju Indonesia Emas 2045” dengan uraian sebagai berikut: 

            <li>Menyediakan Data Statistik Berkualitas dan Insight 
                untuk Perumusan Kebijakan dan Pengambilan Keputusan</li>
            <li>Menguatkan Kepemimpinan BPS dalam penyelenggaraan Sistem Statistik Nasional (SSN)</li>
            <li>Menguatkan kapasitas kelembagaan statistik yang efektif dan efisien</li>
            
        </ul>

    </div>

</div>

            </div>

        </div>

    </div>
</section>