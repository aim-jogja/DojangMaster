<template>
    <div>
      <!-- ══ NAVBAR ══ -->
      <nav class="navbar" :class="{ scrolled: isScrolled }">
        <div class="container">
          <div class="navbar-inner">
            <!-- Logo -->
            <a href="#" class="logo">
              <div class="logo-icon">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <polygon points="20,2 38,12 38,28 20,38 2,28 2,12" fill="none" stroke="#c8a84b" stroke-width="1.5"/>
                  <polygon points="20,8 32,15 32,25 20,32 8,25 8,15" fill="rgba(200,168,75,0.1)" stroke="#c8a84b" stroke-width="0.5"/>
                  <text x="50%" y="54%" font-family="Bebas Neue" font-size="14" fill="#c8a84b" text-anchor="middle" dominant-baseline="middle">DM</text>
                </svg>
              </div>
              <div class="logo-text">Dojang <span>Master</span></div>
            </a>
  
            <!-- Desktop Nav -->
            <ul class="nav-links">
              <li><a href="#features">Fitur</a></li>
              <li><a href="#how">Cara Kerja</a></li>
              <li><a href="#pricing">Harga</a></li>
              <li><a href="#testimonials">Testimoni</a></li>
            </ul>
  
            <!-- Desktop Actions -->
            <div class="nav-actions">
              <a href="/login" class="btn-login">Login</a>
              <a href="#pricing" class="btn-cta">Mulai Gratis</a>
            </div>
  
            <!-- Hamburger -->
            <button
              class="hamburger"
              :class="{ open: menuOpen }"
              @click="menuOpen = !menuOpen"
              aria-label="Menu"
            >
              <span></span><span></span><span></span>
            </button>
          </div>
        </div>
  
        <!-- Mobile Menu -->
        <div class="mobile-menu" :class="{ open: menuOpen }">
          <ul>
            <li><a href="#features" @click="menuOpen = false">Fitur</a></li>
            <li><a href="#how" @click="menuOpen = false">Cara Kerja</a></li>
            <li><a href="#pricing" @click="menuOpen = false">Harga</a></li>
            <li><a href="#testimonials" @click="menuOpen = false">Testimoni</a></li>
          </ul>
          <div class="mobile-actions">
            <a href="/login" class="btn-login">Login</a>
            <a href="#pricing" class="btn-cta" @click="menuOpen = false">Mulai Gratis</a>
          </div>
        </div>
      </nav>
  
      <!-- ══ HERO ══ -->
      <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-grid"></div>
        <div class="hero-diagonal"></div>
        <div class="hero-line"></div>
  
        <div class="container">
          <div class="hero-inner">
            <div class="hero-content">
              <div class="hero-badge">
                <div class="hero-badge-dot"></div>
                <span class="tag">Platform SaaS Dojang #1 Indonesia</span>
              </div>
  
              <h1 class="hero-title">
                Kelola<br>
                <span class="line-gold">Dojang</span><br>
                Anda<br>
                <span class="line-red">Tanpa Batas</span>
              </h1>
  
              <p class="hero-desc">
                Satu platform lengkap untuk manajemen anggota, jadwal latihan, pembayaran, dan promosi sabuk. Dirancang khusus untuk dojang Taekwondo profesional.
              </p>
  
              <div class="hero-actions">
                <a href="#pricing" class="btn-cta">Coba Gratis 14 Hari</a>
                <a href="#how" class="btn-cta-outline">Lihat Demo</a>
              </div>
  
              <div class="hero-stat-row">
                <div>
                  <div class="stat-num">500+</div>
                  <div class="stat-lbl">Dojang Aktif</div>
                </div>
                <div class="hero-stat-divider"></div>
                <div>
                  <div class="stat-num">45K+</div>
                  <div class="stat-lbl">Atlet Terkelola</div>
                </div>
                <div class="hero-stat-divider"></div>
                <div>
                  <div class="stat-num">98%</div>
                  <div class="stat-lbl">Tingkat Retensi</div>
                </div>
              </div>
            </div>
  
            <!-- Dashboard Preview -->
            <div class="hero-visual">
              <div class="hero-card-stack">
                <div class="dashboard-card">
                  <div class="card-header">
                    <span class="card-title">Anggota Aktif Hari Ini</span>
                    <span class="card-status">Live</span>
                  </div>
  
                  <div class="member-row">
                    <div class="avatar">AR</div>
                    <div class="member-info">
                      <div class="member-name">Ahmad Ridwan</div>
                      <div class="member-belt">Sabuk Merah · 3 thn</div>
                    </div>
                    <span class="member-badge badge-active">Aktif</span>
                  </div>
                  <div class="member-row">
                    <div class="avatar">SD</div>
                    <div class="member-info">
                      <div class="member-name">Sari Dewi</div>
                      <div class="member-belt">Sabuk Hijau · 1 thn</div>
                    </div>
                    <span class="member-badge badge-active">Aktif</span>
                  </div>
                  <div class="member-row">
                    <div class="avatar">BW</div>
                    <div class="member-info">
                      <div class="member-name">Bayu Wicaksono</div>
                      <div class="member-belt">Sabuk Putih · Baru</div>
                    </div>
                    <span class="member-badge badge-trial">Trial</span>
                  </div>
  
                  <div class="metric-row">
                    <div class="metric-box">
                      <div class="metric-val">142</div>
                      <div class="metric-lbl">Anggota</div>
                    </div>
                    <div class="metric-box">
                      <div class="metric-val">12</div>
                      <div class="metric-lbl">Sesi/Minggu</div>
                    </div>
                    <div class="metric-box">
                      <div class="metric-val">97%</div>
                      <div class="metric-lbl">Lunas</div>
                    </div>
                  </div>
                </div>
  
                <div class="floating-card">
                  <div class="floating-num">+18%</div>
                  <div class="floating-lbl">Pendapatan bulan ini</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- ══ FEATURES ══ -->
      <section class="section" id="features">
        <div class="container">
          <div class="section-header fade-up">
            <span class="tag">Fitur Unggulan</span>
            <h2 class="section-title">Semua Yang Anda<br><span class="gold">Butuhkan</span></h2>
            <p class="section-desc">Dari manajemen sabuk hingga laporan keuangan — semuanya dalam satu dashboard yang intuitif.</p>
          </div>
  
          <div class="features-grid">
            <div
              v-for="(f, i) in features"
              :key="i"
              class="feature-card fade-up"
            >
              <div class="feature-number">0{{ i + 1 }}</div>
              <div class="feature-icon" v-html="f.icon"></div>
              <div class="feature-name">{{ f.name }}</div>
              <div class="feature-desc">{{ f.desc }}</div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- ══ HOW IT WORKS ══ -->
      <section class="section how-section" id="how">
        <div class="container">
          <div class="how-grid">
            <div>
              <span class="tag">Cara Kerja</span>
              <h2 class="section-title" style="text-align:left; margin:12px 0 40px;">
                Mulai dalam<br><span class="gold">3 Langkah</span>
              </h2>
              <div class="step-list">
                <div
                  v-for="(s, i) in steps"
                  :key="i"
                  class="step-item fade-up"
                >
                  <div class="step-num">0{{ i + 1 }}</div>
                  <div class="step-content">
                    <div class="step-name">{{ s.name }}</div>
                    <div class="step-desc">{{ s.desc }}</div>
                  </div>
                </div>
              </div>
            </div>
  
            <div class="how-visual fade-up">
              <span class="tag" style="display:block; margin-bottom:24px;">Distribusi Sabuk</span>
              <div class="belt-rank-display">
                <div v-for="b in belts" :key="b.name" class="belt-row">
                  <div class="belt-color" :style="{ background: b.color }"></div>
                  <div class="belt-name">{{ b.name }}</div>
                  <div class="belt-count">{{ b.count }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- ══ PRICING ══ -->
      <section class="section" id="pricing">
        <div class="container">
          <div class="section-header fade-up">
            <span class="tag">Paket Harga</span>
            <h2 class="section-title">Pilih Paket<br><span class="gold">Yang Tepat</span></h2>
            <p class="section-desc">Harga transparan tanpa biaya tersembunyi. Upgrade atau downgrade kapan saja.</p>
          </div>
  
          <div class="pricing-grid">
            <div
              v-for="(p, i) in plans"
              :key="i"
              class="price-card fade-up"
              :class="{ featured: p.featured }"
            >
              <div class="price-label">
                {{ p.name }}
                <span v-if="p.featured" class="popular-tag">TERPOPULER</span>
              </div>
              <div class="price-amount"><sup>Rp</sup>{{ p.price }}</div>
              <div class="price-period">per bulan · {{ p.members }}</div>
              <ul class="price-features">
                <li
                  v-for="(f, j) in p.features"
                  :key="j"
                  :class="{ active: f.included }"
                >
                  <svg v-if="f.included" class="check-icon" width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M2 7l3.5 3.5L12 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <svg v-else class="cross-icon" width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M3 3l8 8M11 3l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  </svg>
                  {{ f.text }}
                </li>
              </ul>
              <a href="#" :class="['btn-price', { 'featured-btn': p.featured }]">{{ p.cta }}</a>
            </div>
          </div>
        </div>
      </section>
  
      <!-- ══ TESTIMONIALS ══ -->
      <section class="section testi-section" id="testimonials">
        <div class="container">
          <div class="section-header fade-up">
            <span class="tag">Testimoni</span>
            <h2 class="section-title">Dipercaya Para<br><span class="gold">Pelatih Profesional</span></h2>
          </div>
          <div class="testi-grid">
            <div
              v-for="(t, i) in testimonials"
              :key="i"
              class="testi-card fade-up"
            >
              <div class="quote-mark">"</div>
              <p class="testi-text">{{ t.text }}</p>
              <div class="testi-author">
                <div class="testi-avatar">{{ t.initial }}</div>
                <div>
                  <div class="testi-name">{{ t.name }}</div>
                  <div class="testi-role">{{ t.role }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- ══ CTA ══ -->
      <section class="cta-section">
        <div class="cta-bg"></div>
        <div class="cta-line-top"></div>
        <div class="cta-line-bottom"></div>
        <div class="container" style="position:relative; z-index:2;">
          <span class="tag">Mulai Sekarang</span>
          <h2 class="section-title">Siap Membawa Dojang Anda<br><span class="gold">ke Level Berikutnya?</span></h2>
          <p class="section-desc" style="margin: 0 auto 40px;">
            Bergabunglah dengan 500+ dojang yang telah mempercayakan manajemen mereka kepada Dojang Master.
          </p>
          <div class="cta-actions">
            <a href="#pricing" class="btn-cta-large">Mulai Gratis 14 Hari</a>
            <a href="#" class="btn-cta-outline">Jadwalkan Demo</a>
          </div>
        </div>
      </section>
  
      <!-- ══ FOOTER ══ -->
      <footer class="footer">
        <div class="container">
          <div class="footer-grid">
            <div class="footer-brand">
              <a href="#" class="logo" style="display:inline-flex;">
                <div class="logo-icon">
                  <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="20,2 38,12 38,28 20,38 2,28 2,12" fill="none" stroke="#c8a84b" stroke-width="1.5"/>
                    <text x="50%" y="54%" font-family="Bebas Neue" font-size="14" fill="#c8a84b" text-anchor="middle" dominant-baseline="middle">DM</text>
                  </svg>
                </div>
                <div class="logo-text">Dojang <span>Master</span></div>
              </a>
              <p class="footer-tagline">Platform manajemen dojang Taekwondo terlengkap untuk Indonesia.</p>
            </div>
            <div>
              <div class="footer-col-title">Produk</div>
              <ul class="footer-links">
                <li><a href="#">Fitur</a></li>
                <li><a href="#">Harga</a></li>
                <li><a href="#">Keamanan</a></li>
                <li><a href="#">API</a></li>
              </ul>
            </div>
            <div>
              <div class="footer-col-title">Perusahaan</div>
              <ul class="footer-links">
                <li><a href="#">Tentang Kami</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Karir</a></li>
                <li><a href="#">Kontak</a></li>
              </ul>
            </div>
            <div>
              <div class="footer-col-title">Dukungan</div>
              <ul class="footer-links">
                <li><a href="#">Dokumentasi</a></li>
                <li><a href="#">Pusat Bantuan</a></li>
                <li><a href="#">Status</a></li>
                <li><a href="#">Komunitas</a></li>
              </ul>
            </div>
          </div>
          <div class="footer-bottom">
            <div class="footer-copy">© 2024 Dojang Master. Hak cipta dilindungi.</div>
            <div class="footer-legal">
              <a href="#">Privasi</a>
              <a href="#">Syarat &amp; Ketentuan</a>
              <a href="#">Cookie</a>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  
  // ── Reactive state ──────────────────────────────────────────────────────────
  const isScrolled = ref(false)
  const menuOpen   = ref(false)
  
  // ── Scroll handler ──────────────────────────────────────────────────────────
  const handleScroll = () => {
    isScrolled.value = window.scrollY > 40
  }
  
  // ── Fade-up intersection observer ───────────────────────────────────────────
  onMounted(() => {
    window.addEventListener('scroll', handleScroll)
  
    const obs = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) {
            e.target.classList.add('visible')
            obs.unobserve(e.target)
          }
        })
      },
      { threshold: 0.12 }
    )
    document.querySelectorAll('.fade-up').forEach((el) => obs.observe(el))
  })
  
  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
  })
  
  // ── Static data ─────────────────────────────────────────────────────────────
  const features = [
    {
      name: 'Manajemen Anggota',
      desc: 'Data lengkap setiap atlet: foto, riwayat sabuk, absensi, dan dokumen. Semua terpusat dan mudah diakses kapan saja.',
      icon: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="18" cy="16" r="6" stroke="currentColor" stroke-width="1.5"/>
        <path d="M6 38c0-6.627 5.373-12 12-12h8c6.627 0 12 5.373 12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        <circle cx="34" cy="14" r="4" stroke="currentColor" stroke-width="1.5"/>
        <path d="M38 28h2c4 0 7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      </svg>`,
    },
    {
      name: 'Jadwal & Kelas',
      desc: 'Kelola jadwal latihan harian, kelas khusus, dan sesi ujian sabuk dengan kalender visual yang mudah dikonfigurasi.',
      icon: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="6" y="10" width="36" height="32" rx="2" stroke="currentColor" stroke-width="1.5"/>
        <path d="M6 18h36M16 6v8M32 6v8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        <rect x="14" y="24" width="6" height="6" rx="1" fill="currentColor" opacity="0.4"/>
        <rect x="24" y="24" width="6" height="6" rx="1" fill="currentColor" opacity="0.4"/>
        <rect x="14" y="34" width="6" height="4" rx="1" fill="currentColor" opacity="0.2"/>
      </svg>`,
    },
    {
      name: 'Pembayaran Otomatis',
      desc: 'Tagihan iuran bulanan otomatis dengan notifikasi WhatsApp/email. Pantau status pembayaran secara real-time.',
      icon: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="4" y="12" width="40" height="28" rx="3" stroke="currentColor" stroke-width="1.5"/>
        <path d="M4 20h40" stroke="currentColor" stroke-width="1.5"/>
        <rect x="10" y="26" width="10" height="6" rx="1" fill="currentColor" opacity="0.4"/>
        <circle cx="34" cy="29" r="3" stroke="currentColor" stroke-width="1.5"/>
        <circle cx="38" cy="29" r="3" stroke="currentColor" stroke-width="1.5"/>
      </svg>`,
    },
    {
      name: 'Sistem Sabuk & Promosi',
      desc: 'Rekam pencapaian sabuk setiap atlet, jadwalkan ujian, dan kirim sertifikat digital otomatis setelah promosi.',
      icon: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M24 6l4 10h10l-8 6 3 10-9-6-9 6 3-10-8-6h10z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
        <path d="M16 34l-4 8h24l-4-8" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" opacity="0.5"/>
      </svg>`,
    },
    {
      name: 'Laporan & Analitik',
      desc: 'Dashboard analitik lengkap: tren pertumbuhan anggota, pendapatan, tingkat kehadiran, dan prediksi churn.',
      icon: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 38V28M14 38V20M22 38V24M30 38V14M38 38V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M6 28l8-8 8 4 8-10 8-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
      </svg>`,
    },
    {
      name: 'Komunikasi Atlet',
      desc: 'Kirim pengumuman, pengingat latihan, dan hasil evaluasi langsung ke semua anggota via WhatsApp atau email.',
      icon: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M8 8h32a2 2 0 012 2v20a2 2 0 01-2 2H16l-8 6V10a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
        <path d="M16 20h16M16 26h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      </svg>`,
    },
  ]
  
  const steps = [
    {
      name: 'Daftarkan Dojang Anda',
      desc: 'Buat akun dalam 2 menit. Masukkan info dojang Anda dan sistem langsung siap digunakan.',
    },
    {
      name: 'Input Data Anggota',
      desc: 'Import data anggota existing via CSV atau input satu per satu. Profil sabuk langsung terkonfigurasi.',
    },
    {
      name: 'Kelola & Pantau',
      desc: 'Gunakan dashboard untuk memantau semua aktivitas, pembayaran, dan perkembangan atlet secara real-time.',
    },
  ]
  
  const belts = [
    { name: 'Sabuk Hitam',  color: '#1a1a1a', count: 8  },
    { name: 'Sabuk Merah',  color: '#c0392b', count: 14 },
    { name: 'Sabuk Biru',   color: '#2980b9', count: 22 },
    { name: 'Sabuk Hijau',  color: '#27ae60', count: 31 },
    { name: 'Sabuk Kuning', color: '#f39c12', count: 38 },
    { name: 'Sabuk Putih',  color: '#ecf0f1', count: 29 },
  ]
  
  const plans = [
    {
      name: 'Starter',
      price: '149K',
      members: 'Hingga 50 anggota',
      featured: false,
      cta: 'Mulai Gratis',
      features: [
        { text: 'Manajemen 50 anggota',  included: true  },
        { text: 'Jadwal & absensi',      included: true  },
        { text: 'Laporan dasar',         included: true  },
        { text: 'Pembayaran otomatis',   included: false },
        { text: 'Multi pelatih',         included: false },
        { text: 'API akses',             included: false },
      ],
    },
    {
      name: 'Professional',
      price: '349K',
      members: 'Hingga 200 anggota',
      featured: true,
      cta: 'Pilih Professional',
      features: [
        { text: 'Manajemen 200 anggota', included: true  },
        { text: 'Jadwal & absensi',      included: true  },
        { text: 'Laporan lengkap',       included: true  },
        { text: 'Pembayaran otomatis',   included: true  },
        { text: 'Multi pelatih (5)',      included: true  },
        { text: 'API akses',             included: false },
      ],
    },
    {
      name: 'Enterprise',
      price: 'Custom',
      members: 'Anggota tak terbatas',
      featured: false,
      cta: 'Hubungi Kami',
      features: [
        { text: 'Anggota tak terbatas',          included: true },
        { text: 'Jadwal & absensi',              included: true },
        { text: 'Laporan & analitik lanjutan',   included: true },
        { text: 'Pembayaran otomatis',           included: true },
        { text: 'Multi pelatih tak terbatas',    included: true },
        { text: 'API akses penuh',               included: true },
      ],
    },
  ]
  
  const testimonials = [
    {
      text: 'Sejak pakai Dojang Master, saya tidak perlu lagi repot dengan spreadsheet. Pembayaran iuran terpantau otomatis dan anggota pun lebih disiplin karena ada pengingat otomatis.',
      name: 'Pelatih Hendra S.',
      role: 'Kepala Pelatih · Dojang Garuda Jakarta',
      initial: 'HS',
    },
    {
      text: 'Fitur rekap sabuk dan jadwal ujian sangat membantu. Semua histori pencapaian atlet tersimpan rapi. Dashboard-nya intuitif, bahkan staf administrasi saya bisa langsung paham.',
      name: 'Kuswanto, S.Pd.',
      role: 'Owner · Taekwondo Club Surabaya',
      initial: 'KW',
    },
    {
      text: 'Laporan keuangan bulanan yang rapi membuat saya bisa lebih fokus melatih. Dojang Master benar-benar menghemat waktu administratif saya lebih dari 10 jam per minggu.',
      name: 'Master Yunita R.',
      role: 'Pelatih Bersertifikat · Dojang Elang Bandung',
      initial: 'YR',
    },
  ]
  </script>
  
  <style>
  /* ── Google Fonts ── */
  @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rajdhani:wght@400;500;600;700&family=Noto+Sans:wght@300;400;500&display=swap');
  
  :root {
    --black: #0a0a0b;
    --carbon: #111214;
    --iron: #1a1c20;
    --steel: #252830;
    --gold: #c8a84b;
    --gold-light: #e8c96a;
    --gold-dim: #8a7030;
    --red: #c0392b;
    --red-glow: #e74c3c;
    --white: #f0ede8;
    --muted: #7a7872;
    --border: rgba(200, 168, 75, 0.15);
    --border-subtle: rgba(255, 255, 255, 0.06);
  }
  
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  
  html { scroll-behavior: smooth; }
  
  body {
    font-family: 'Noto Sans', sans-serif;
    background: var(--black);
    color: var(--white);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
  }
  
  /* ── SCROLLBAR ── */
  ::-webkit-scrollbar { width: 4px; }
  ::-webkit-scrollbar-track { background: var(--black); }
  ::-webkit-scrollbar-thumb { background: var(--gold-dim); border-radius: 2px; }
  
  /* ── UTILITY ── */
  .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
  .gold { color: var(--gold); }
  .tag {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 600;
    font-size: 11px;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--gold);
  }
  
  /* ══════════════════════════════════
     NAVBAR
  ══════════════════════════════════ */
  .navbar {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    transition: all 0.4s ease;
  }
  .navbar.scrolled {
    background: rgba(10, 10, 11, 0.92);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
  }
  .navbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 72px;
  }
  .logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
  }
  .logo-icon { width: 40px; height: 40px; position: relative; }
  .logo-icon svg { width: 100%; height: 100%; }
  .logo-text {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 26px;
    letter-spacing: 0.05em;
    color: var(--white);
    line-height: 1;
  }
  .logo-text span { color: var(--gold); }
  
  .nav-links {
    display: flex;
    align-items: center;
    gap: 36px;
    list-style: none;
  }
  .nav-links a {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 500;
    font-size: 14px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--muted);
    text-decoration: none;
    transition: color 0.2s;
    position: relative;
  }
  .nav-links a::after {
    content: '';
    position: absolute;
    bottom: -4px; left: 0;
    width: 0; height: 1px;
    background: var(--gold);
    transition: width 0.3s;
  }
  .nav-links a:hover { color: var(--white); }
  .nav-links a:hover::after { width: 100%; }
  
  .nav-actions { display: flex; align-items: center; gap: 12px; }
  
  .btn-login {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--gold);
    background: transparent;
    border: 1px solid var(--border);
    padding: 9px 24px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s;
  }
  .btn-login:hover {
    border-color: var(--gold);
    background: rgba(200, 168, 75, 0.08);
    color: var(--gold-light);
  }
  
  .btn-cta {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--black);
    background: var(--gold);
    border: 1px solid var(--gold);
    padding: 9px 24px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s;
    display: inline-block;
  }
  .btn-cta:hover {
    background: var(--gold-light);
    border-color: var(--gold-light);
    transform: translateY(-1px);
  }
  .btn-cta-outline {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--gold);
    background: transparent;
    border: 1px solid var(--gold);
    padding: 14px 36px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s;
    display: inline-block;
  }
  .btn-cta-outline:hover { background: rgba(200, 168, 75, 0.1); }
  
  /* Hamburger */
  .hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    background: none;
    border: none;
    padding: 4px;
  }
  .hamburger span {
    display: block; width: 24px; height: 1.5px;
    background: var(--white);
    transition: all 0.3s;
  }
  .hamburger.open span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); }
  .hamburger.open span:nth-child(2) { opacity: 0; }
  .hamburger.open span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); }
  
  .mobile-menu {
    display: none;
    background: var(--carbon);
    border-top: 1px solid var(--border);
    padding: 24px;
  }
  .mobile-menu.open { display: block; }
  .mobile-menu ul { list-style: none; display: flex; flex-direction: column; gap: 20px; }
  .mobile-menu a {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 600;
    font-size: 16px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--muted);
    text-decoration: none;
  }
  .mobile-menu a:hover { color: var(--gold); }
  .mobile-menu .mobile-actions {
    display: flex; flex-direction: column; gap: 12px; margin-top: 28px;
  }
  .mobile-menu .btn-login,
  .mobile-menu .btn-cta { text-align: center; }
  
  /* ══════════════════════════════════
     HERO
  ══════════════════════════════════ */
  .hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    padding: 120px 0 80px;
  }
  .hero-bg {
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 60% 60% at 70% 50%, rgba(192, 57, 43, 0.06) 0%, transparent 70%),
      radial-gradient(ellipse 40% 80% at 5% 50%, rgba(200, 168, 75, 0.04) 0%, transparent 70%),
      linear-gradient(180deg, var(--black) 0%, var(--carbon) 100%);
  }
  .hero-grid {
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(200, 168, 75, 0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(200, 168, 75, 0.04) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 0%, transparent 100%);
  }
  .hero-diagonal {
    position: absolute;
    top: 0; right: -10%;
    width: 45%; height: 100%;
    background: linear-gradient(135deg, transparent 0%, rgba(200, 168, 75, 0.02) 50%, transparent 100%);
    transform: skewX(-8deg);
    pointer-events: none;
  }
  .hero-line {
    position: absolute;
    left: 0; right: 0; bottom: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold-dim), transparent);
  }
  .hero-inner {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
  }
  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid var(--border);
    padding: 6px 14px 6px 8px;
    margin-bottom: 32px;
  }
  .hero-badge-dot {
    width: 6px; height: 6px;
    background: var(--red);
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
  }
  @keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(0.8); }
  }
  .hero-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(56px, 7vw, 96px);
    line-height: 0.92;
    letter-spacing: 0.02em;
    margin-bottom: 28px;
  }
  .hero-title .line-gold { color: var(--gold); }
  .hero-title .line-red {
    color: var(--white);
    -webkit-text-stroke: 1px rgba(255, 255, 255, 0.3);
  }
  .hero-desc {
    font-size: 16px;
    line-height: 1.7;
    color: var(--muted);
    max-width: 440px;
    margin-bottom: 40px;
    font-weight: 300;
  }
  .hero-actions { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
  .hero-stat-row {
    display: flex;
    align-items: center;
    gap: 32px;
    margin-top: 56px;
    padding-top: 32px;
    border-top: 1px solid var(--border-subtle);
  }
  .hero-stat-divider { width: 1px; height: 36px; background: var(--border-subtle); }
  .stat-num {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 32px;
    color: var(--gold);
    letter-spacing: 0.05em;
    line-height: 1;
  }
  .stat-lbl {
    font-family: 'Rajdhani', sans-serif;
    font-size: 12px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--muted);
    margin-top: 2px;
  }
  
  /* Hero Visual */
  .hero-visual { position: relative; display: flex; justify-content: center; align-items: center; }
  .hero-card-stack { position: relative; width: 100%; max-width: 420px; }
  .dashboard-card {
    background: var(--iron);
    border: 1px solid var(--border-subtle);
    border-radius: 2px;
    padding: 24px;
    position: relative;
    overflow: hidden;
  }
  .dashboard-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--gold-dim), var(--gold), var(--gold-dim));
  }
  .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
  .card-title {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 600;
    font-size: 12px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--muted);
  }
  .card-status {
    font-family: 'Rajdhani', sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.1em;
    color: #4caf50;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .card-status::before { content: ''; width: 5px; height: 5px; background: #4caf50; border-radius: 50%; }
  .member-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-subtle);
  }
  .member-row:last-child { border-bottom: none; }
  .avatar {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, var(--gold-dim), var(--steel));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 13px;
    color: var(--gold-light);
    flex-shrink: 0;
  }
  .member-info { flex: 1; }
  .member-name { font-family: 'Rajdhani', sans-serif; font-weight: 600; font-size: 14px; color: var(--white); }
  .member-belt { font-size: 11px; color: var(--muted); }
  .member-badge {
    font-family: 'Rajdhani', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    padding: 2px 8px;
    border-radius: 1px;
  }
  .badge-active { background: rgba(76, 175, 80, 0.15); color: #4caf50; }
  .badge-trial  { background: rgba(200, 168, 75, 0.15); color: var(--gold); }
  .metric-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 12px; }
  .metric-box { background: var(--steel); padding: 12px; text-align: center; }
  .metric-val { font-family: 'Bebas Neue', sans-serif; font-size: 24px; color: var(--gold); letter-spacing: 0.05em; }
  .metric-lbl {
    font-family: 'Rajdhani', sans-serif;
    font-size: 10px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--muted);
    margin-top: 2px;
  }
  .floating-card {
    position: absolute;
    right: -24px; bottom: -20px;
    background: var(--steel);
    border: 1px solid var(--border);
    padding: 14px 18px;
    min-width: 160px;
    animation: floatY 4s ease-in-out infinite;
  }
  @keyframes floatY {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
  }
  .floating-num { font-family: 'Bebas Neue', sans-serif; font-size: 28px; color: var(--red-glow); letter-spacing: 0.05em; }
  .floating-lbl {
    font-family: 'Rajdhani', sans-serif;
    font-size: 11px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--muted);
  }
  
  /* ══════════════════════════════════
     FEATURES
  ══════════════════════════════════ */
  .section { padding: 100px 0; position: relative; }
  .section-header { text-align: center; margin-bottom: 64px; }
  .section-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(40px, 5vw, 64px);
    letter-spacing: 0.03em;
    line-height: 1;
    margin: 12px 0 16px;
  }
  .section-desc { font-size: 15px; color: var(--muted); max-width: 480px; margin: 0 auto; line-height: 1.7; }
  .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: var(--border-subtle); }
  .feature-card {
    background: var(--carbon);
    padding: 40px 32px;
    position: relative;
    overflow: hidden;
    transition: background 0.3s;
    cursor: default;
  }
  .feature-card:hover { background: var(--iron); }
  .feature-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 0; height: 2px;
    background: var(--gold);
    transition: width 0.4s ease;
  }
  .feature-card:hover::before { width: 100%; }
  .feature-number { font-family: 'Bebas Neue', sans-serif; font-size: 11px; letter-spacing: 0.2em; color: var(--gold-dim); margin-bottom: 24px; }
  .feature-icon { width: 48px; height: 48px; margin-bottom: 20px; color: var(--gold); }
  .feature-name { font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 18px; letter-spacing: 0.05em; color: var(--white); margin-bottom: 12px; }
  .feature-desc { font-size: 14px; line-height: 1.65; color: var(--muted); }
  
  /* ══════════════════════════════════
     HOW IT WORKS
  ══════════════════════════════════ */
  .how-section { background: var(--carbon); }
  .how-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
  .step-list { display: flex; flex-direction: column; gap: 0; }
  .step-item {
    display: flex;
    gap: 24px;
    padding: 28px 0;
    border-bottom: 1px solid var(--border-subtle);
    position: relative;
    cursor: default;
  }
  .step-item:last-child { border-bottom: none; }
  .step-num {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 40px;
    color: var(--iron);
    line-height: 1;
    flex-shrink: 0;
    width: 48px;
    text-align: right;
    transition: color 0.3s;
  }
  .step-item:hover .step-num { color: var(--gold-dim); }
  .step-name { font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 17px; letter-spacing: 0.05em; color: var(--white); margin-bottom: 6px; }
  .step-desc { font-size: 14px; line-height: 1.6; color: var(--muted); }
  .how-visual {
    background: var(--iron);
    border: 1px solid var(--border-subtle);
    padding: 40px;
    position: relative;
  }
  .how-visual::after {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 0; height: 0;
    border-style: solid;
    border-width: 0 32px 32px 0;
    border-color: transparent var(--carbon) transparent transparent;
  }
  .belt-rank-display { display: flex; flex-direction: column; gap: 12px; }
  .belt-row { display: flex; align-items: center; gap: 14px; padding: 12px 16px; background: var(--steel); transition: all 0.3s; }
  .belt-row:hover { background: var(--black); }
  .belt-color { width: 48px; height: 8px; border-radius: 1px; flex-shrink: 0; }
  .belt-name { font-family: 'Rajdhani', sans-serif; font-weight: 600; font-size: 14px; color: var(--white); flex: 1; }
  .belt-count { font-family: 'Bebas Neue', sans-serif; font-size: 18px; color: var(--gold); letter-spacing: 0.05em; }
  
  /* ══════════════════════════════════
     PRICING
  ══════════════════════════════════ */
  .pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: var(--border-subtle); max-width: 960px; margin: 0 auto; }
  .price-card { background: var(--carbon); padding: 40px 32px; position: relative; overflow: hidden; }
  .price-card.featured { background: var(--iron); z-index: 1; margin: -1px 0; }
  .price-card.featured::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
  }
  .price-label {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .price-label .popular-tag { background: var(--gold); color: var(--black); font-size: 9px; padding: 2px 8px; letter-spacing: 0.12em; }
  .price-amount { font-family: 'Bebas Neue', sans-serif; font-size: 52px; color: var(--white); letter-spacing: 0.02em; line-height: 1; margin-bottom: 4px; }
  .price-amount sup { font-family: 'Rajdhani', sans-serif; font-size: 20px; font-weight: 600; vertical-align: super; margin-right: 2px; }
  .price-period { font-family: 'Rajdhani', sans-serif; font-size: 13px; color: var(--muted); margin-bottom: 28px; }
  .price-features { list-style: none; display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px; }
  .price-features li { font-family: 'Rajdhani', sans-serif; font-weight: 500; font-size: 14px; color: var(--muted); display: flex; align-items: center; gap: 10px; }
  .price-features li.active { color: var(--white); }
  .check-icon { color: var(--gold); flex-shrink: 0; }
  .cross-icon { color: #444; flex-shrink: 0; }
  .btn-price {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 12px 24px;
    width: 100%;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--muted);
    cursor: pointer;
    transition: all 0.25s;
    text-align: center;
    text-decoration: none;
    display: block;
  }
  .btn-price:hover { border-color: var(--gold-dim); color: var(--gold); }
  .btn-price.featured-btn { background: var(--gold); border-color: var(--gold); color: var(--black); }
  .btn-price.featured-btn:hover { background: var(--gold-light); }
  
  /* ══════════════════════════════════
     TESTIMONIALS
  ══════════════════════════════════ */
  .testi-section { background: var(--black); }
  .testi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: var(--border-subtle); }
  .testi-card { background: var(--carbon); padding: 36px 32px; }
  .quote-mark { font-family: 'Bebas Neue', sans-serif; font-size: 64px; color: var(--gold-dim); line-height: 0.6; margin-bottom: 20px; }
  .testi-text { font-size: 15px; line-height: 1.7; color: var(--muted); margin-bottom: 24px; font-style: italic; }
  .testi-author { display: flex; align-items: center; gap: 12px; }
  .testi-avatar {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, var(--gold-dim), var(--steel));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 16px;
    color: var(--gold-light);
  }
  .testi-name { font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 15px; color: var(--white); }
  .testi-role { font-size: 12px; color: var(--muted); }
  
  /* ══════════════════════════════════
     CTA SECTION
  ══════════════════════════════════ */
  .cta-section { position: relative; padding: 120px 0; overflow: hidden; text-align: center; }
  .cta-bg {
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 60% 80% at 50% 50%, rgba(200, 168, 75, 0.06) 0%, transparent 70%),
      var(--carbon);
  }
  .cta-line-top, .cta-line-bottom {
    position: absolute;
    left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold-dim), transparent);
  }
  .cta-line-top { top: 0; }
  .cta-line-bottom { bottom: 0; }
  .cta-section .section-title { margin-bottom: 16px; }
  .cta-section .section-desc { margin-bottom: 40px; }
  .cta-actions { display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap; }
  .btn-cta-large {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--black);
    background: var(--gold);
    border: 1px solid var(--gold);
    padding: 16px 48px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.25s;
    display: inline-block;
  }
  .btn-cta-large:hover { background: var(--gold-light); border-color: var(--gold-light); }
  
  /* ══════════════════════════════════
     FOOTER
  ══════════════════════════════════ */
  .footer { background: var(--black); border-top: 1px solid var(--border-subtle); padding: 64px 0 32px; }
  .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px; margin-bottom: 48px; }
  .footer-tagline { font-size: 14px; color: var(--muted); line-height: 1.7; margin-top: 16px; max-width: 240px; }
  .footer-col-title { font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 12px; letter-spacing: 0.2em; text-transform: uppercase; color: var(--gold-dim); margin-bottom: 20px; }
  .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
  .footer-links a { font-size: 14px; color: var(--muted); text-decoration: none; transition: color 0.2s; }
  .footer-links a:hover { color: var(--white); }
  .footer-bottom { border-top: 1px solid var(--border-subtle); padding-top: 28px; display: flex; align-items: center; justify-content: space-between; }
  .footer-copy { font-size: 13px; color: var(--muted); }
  .footer-legal { display: flex; gap: 24px; }
  .footer-legal a { font-size: 13px; color: var(--muted); text-decoration: none; }
  .footer-legal a:hover { color: var(--gold); }
  
  /* ══════════════════════════════════
     ANIMATIONS
  ══════════════════════════════════ */
  .fade-up { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
  .fade-up.visible { opacity: 1; transform: translateY(0); }
  
  /* ══════════════════════════════════
     RESPONSIVE
  ══════════════════════════════════ */
  @media (max-width: 1024px) {
    .features-grid { grid-template-columns: repeat(2, 1fr); }
    .hero-inner { gap: 48px; }
    .footer-grid { grid-template-columns: 1fr 1fr; gap: 40px; }
    .pricing-grid { grid-template-columns: 1fr; max-width: 400px; }
    .price-card.featured { margin: 0; }
  }
  @media (max-width: 768px) {
    .nav-links, .nav-actions { display: none; }
    .hamburger { display: flex; }
    .hero-inner { grid-template-columns: 1fr; gap: 48px; }
    .hero-visual { display: none; }
    .hero { padding: 100px 0 60px; min-height: auto; }
    .hero-stat-row { gap: 20px; }
    .how-grid { grid-template-columns: 1fr; }
    .how-visual { display: none; }
    .features-grid { grid-template-columns: 1fr; }
    .testi-grid { grid-template-columns: 1fr; }
    .footer-grid { grid-template-columns: 1fr; }
    .footer-bottom { flex-direction: column; gap: 16px; text-align: center; }
    .section { padding: 64px 0; }
  }
  @media (max-width: 480px) {
    .hero-actions { flex-direction: column; align-items: flex-start; }
    .btn-cta, .btn-cta-outline { width: 100%; text-align: center; }
    .cta-actions { flex-direction: column; }
  }
  </style>