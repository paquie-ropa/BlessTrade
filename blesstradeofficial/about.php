<?php
// BlessTrade Township Marketplace about.php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<div class="container my-5 text-start animate-in fade-in">
    
    <!-- Title Section -->
    <div class="text-center mb-5">
        <h1 class="display-6 fw-extrabold text-slate-900 font-display">About BlessTrade</h1>
        <p class="text-slate-500 max-width-md mx-auto" style="font-size: 0.95rem;">Empowering South African township traders with reliable, sustainable technology</p>
    </div>

    <!-- Our Story -->
    <div class="row g-4 align-items-stretch mb-5">
        <div class="col-md-7">
            <div class="bg-white border rounded-4 p-4 p-md-5 h-100 d-flex flex-column justify-content-center">
                <span class="badge bg-emerald-100 text-emerald-800 uppercase px-2 py-1 mb-2 fw-bold align-self-start" style="font-size:9px; letter-spacing:0.08em;">Established 2026</span>
                <h3 class="font-display fw-extrabold text-slate-900 mb-3">Our Platform Story</h3>
                <p class="text-slate-600 leading-relaxed mb-4" style="font-size: 0.9rem;">
                    BlessTrade is a tailored peer-to-peer e-commerce marketplace platform engineered entirely to empower residents and micro-merchants within South African townships. Founded by Blessing, the platform acts as a secure, neighborhood-bound intermediary connecting buyers and sellers directly.
                </p>
                <p class="text-slate-600 leading-relaxed mb-0" style="font-size: 0.9rem;">
                    Through our service, items are recycled back into communities, micro-entrepreneurs make instant cash without heavy listing fees, and buyers purchase devices, appliances, and fashion items at honest, fractions of retail costs.
                </p>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="bg-emerald-900 text-white rounded-4 p-4 p-md-5 h-100 d-flex flex-column justify-content-center border border-emerald-800">
                <i data-lucide="sparkles" class="text-warning mb-3" style="width:36px; height:36px;"></i>
                <h4 class="font-display fw-extrabold mb-3">Our Safe Mission</h4>
                <p class="text-emerald-100 leading-relaxed mb-0" style="font-size:0.88rem;">
                    To stimulate township economic flow by establishing a modernized, reliable digital hub where buyers and sellers can browse listings, communicate directly, verify device working conditions securely, and conclude transactions with safe handshakes. No micro-merchant should ever be excluded or priced out.
                </p>
            </div>
        </div>
    </div>

    <!-- Delivery & Logistics -->
    <div class="bg-slate-900 text-white rounded-4 p-4 p-md-5 mb-5 border shadow-sm">
        <h3 class="font-display fw-extrabold mb-4 d-flex align-items-center gap-2">
            <i data-lucide="truck" class="text-emerald-400"></i> Delivery & Logistics Framework
        </h3>
        <p class="text-slate-300 mb-4" style="font-size: 0.9rem;">
            Because security is core to local peer-to-peer interactions, we orchestrate township micro-distribution support networks led by our localized operations coordinators.
        </p>
        
        <div class="row g-4 text-start">
            <div class="col-md-3">
                <div class="bg-slate-800 p-4 rounded-4 border border-slate-700 h-100">
                    <span class="fw-bold text-emerald-400 d-block mb-1" style="font-size: 0.95rem;">📍 Coverage Hubs</span>
                    <span class="text-slate-300 d-block" style="font-size: 0.8rem; line-height: 1.6;">
                        Active sectors within Soweto (Orlando, Jabulani, Pimville, Diepkloof), Alexandra, Orange Farm, and Tembisa.
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-slate-800 p-4 rounded-4 border border-slate-700 h-100">
                    <span class="fw-bold text-emerald-400 d-block mb-1" style="font-size: 0.95rem;">🚚 Lead Delivery Times</span>
                    <span class="text-slate-300 d-block" style="font-size: 0.8rem; line-height: 1.6;">
                        Typically 1 to 3 business days for items routed via automated township merchant coordination transfers.
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-slate-800 p-4 rounded-4 border border-slate-700 h-100">
                    <span class="fw-bold text-emerald-400 d-block mb-1" style="font-size: 0.95rem;">💵 Settlement Standard</span>
                    <span class="text-slate-300 d-block" style="font-size: 0.8rem; line-height: 1.6;">
                        Cash on Delivery (COD) acts as our default fallback. Inspect all specifications face-to-face before cash exchanges.
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-slate-800 p-4 rounded-4 border border-slate-700 h-100">
                    <span class="fw-bold text-emerald-400 d-block mb-1" style="font-size: 0.95rem;">🤝 Handshake Safety</span>
                    <span class="text-slate-300 d-block" style="font-size: 0.8rem; line-height: 1.6;">
                        Meetups take place at busy public locations or township transport nodes like Maponya Mall, Alex Junction, or Tembisa Plazas.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Meet The Team -->
    <div class="my-5 text-center">
        <h3 class="font-display fw-extrabold text-slate-900 mb-4 d-flex align-items-center justify-content-center gap-2">
            <i data-lucide="users" class="text-emerald-600"></i> Meet the BlessTrade Founders
        </h3>
        
        <div class="row g-4 justify-content-center">
            <!-- Team 1 -->
            <div class="col-md-4">
                <div class="bg-white border rounded-4 p-4 text-center shadow-xs">
                    <div class="bg-emerald-100 text-emerald-700 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px; font-weight:800; font-size:1.4rem;">
                        B
                    </div>
                    <h5 class="fw-bold text-slate-800 mb-1" style="font-size:0.95rem;">Blessing</h5>
                    <p class="text-emerald-600 fw-bold mb-3" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">Founder & Principal Dev</p>
                    <p class="text-muted mb-0 leading-relaxed font-sans" style="font-size:0.8rem;">
                        Focused on building localized, secure, ultra-light Peer-to-Peer web applications for South African digital entrepreneurs.
                    </p>
                </div>
            </div>
            <!-- Team 2 -->
            <div class="col-md-4">
                <div class="bg-white border rounded-4 p-4 text-center shadow-xs">
                    <div class="bg-indigo-100 text-indigo-700 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px; font-weight:800; font-size:1.4rem;">
                        TN
                    </div>
                    <h5 class="fw-bold text-slate-800 mb-1" style="font-size:0.95rem;">Thabo Nkosi</h5>
                    <p class="text-indigo-600 fw-bold mb-3" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">Operations Supervisor</p>
                    <p class="text-muted mb-0 leading-relaxed font-sans" style="font-size:0.8rem;">
                        Coordinating seller listings, resolving verification tickets, and supporting logistics and handovers in Soweto hubs.
                    </p>
                </div>
            </div>
            <!-- Team 3 -->
            <div class="col-md-4">
                <div class="bg-white border rounded-4 p-4 text-center shadow-xs">
                    <div class="bg-purple-100 text-purple-700 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px; font-weight:800; font-size:1.4rem;">
                        NK
                    </div>
                    <h5 class="fw-bold text-slate-800 mb-1" style="font-size:0.95rem;">Nomsa Khumalo</h5>
                    <p class="text-purple-600 fw-bold mb-3" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">Customer Relations</p>
                    <p class="text-muted mb-0 leading-relaxed font-sans" style="font-size:0.8rem;">
                        Helping buyers and sellers on messaging channels to handle item listing obstacles, checkouts, and system passwords.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
