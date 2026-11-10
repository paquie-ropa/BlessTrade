<?php
// BlessTrade Township Marketplace contact.php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<div class="container my-5 text-start animate-in fade-in">
    
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-6 fw-extrabold text-slate-900 font-display">Contact BlessTrade Support</h1>
        <p class="text-slate-500 max-width-md mx-auto" style="font-size: 0.95rem;">Our support channels are open for micro-entrepreneurs and township shoppers</p>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Contact Information Block -->
        <div class="col-md-7">
            <div class="bg-white border rounded-4 p-4 p-md-5 shadow-xs h-100">
                <h4 class="font-display fw-extrabold text-slate-900 mb-4 pb-2 border-bottom">📍 Live Operations & Support</h4>
                
                <div class="space-y-4">
                    <!-- Location item -->
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-emerald-50 text-emerald-700 p-2.5 rounded-3 shrink-0">
                            <i data-lucide="map-pin" style="width:20px; height:20px;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-slate-800 mb-0.5" style="font-size:0.92rem;">Our Physical Coordinate Address</h6>
                            <p class="text-slate-500 mb-0 leading-normal" style="font-size:0.85rem;">
                                Gauteng Province, South Africa (Operational teams active in Soweto-Jabulani, Alexandra Junction, and Tembisa Plazas).
                            </p>
                        </div>
                    </div>

                    <!-- Hotline item -->
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-emerald-50 text-emerald-700 p-2.5 rounded-3 shrink-0">
                            <i data-lucide="phone-call" style="width:20px; height:20px;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-slate-800 mb-0.5" style="font-size:0.92rem;">Support Cellular Line</h6>
                            <p class="text-emerald-700 fw-bold font-mono mb-0" style="font-size:1rem;">
                                +27 81 234 5678
                            </p>
                            <span class="text-muted d-block" style="font-size:0.75rem;">Active daily on phone & messaging lines from 08:00 to 18:00 SAS time.</span>
                        </div>
                    </div>

                    <!-- Email item -->
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-emerald-50 text-emerald-700 p-2.5 rounded-3 shrink-0">
                            <i data-lucide="mail-check" style="width:20px; height:20px;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-slate-800 mb-0.5" style="font-size:0.92rem;">General Corporate Inquiry</h6>
                            <p class="text-emerald-700 fw-bold font-mono mb-0" style="font-size:0.95rem;">
                                info@blesstrade.co.za
                            </p>
                            <span class="text-muted d-block" style="font-size:0.75rem;">For administrative disputes and partnerships.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Quick Assistance Box -->
        <div class="col-md-5">
            <div class="bg-slate-900 text-white rounded-4 p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="font-display fw-extrabold mb-3 d-flex align-items-center gap-2">
                        <i data-lucide="help-circle" class="text-warning"></i> Need Manual Listing Help?
                    </h4>
                    <p class="text-slate-300 mb-4" style="font-size: 0.85rem; line-height: 1.6;">
                        Our core operations team (Thabo Nkosi and Nomsa Khumalo) are dedicated daily to assisting micro-merchants list their products! If you are finding it difficult to:
                    </p>
                    <ul class="text-slate-300 space-y-2 ps-3 mb-4" style="font-size: 0.8rem; line-height: 1.8;">
                        <li>📸 Crop product snapshots</li>
                        <li>✍️ Compose catchy descriptions</li>
                        <li>⚙️ Fill in numeric catalog details</li>
                        <li>🔑 Reset your passwords</li>
                    </ul>
                    <p class="text-amber-300 fw-bold mb-0" style="font-size: 0.82rem;">
                        Simply send us a quick messaging text and we will list it for you live!
                    </p>
                </div>
                
                <div class="pt-4 border-top border-slate-800 mt-4">
                    <button onclick="window.location.href='tel:+27812345678'" class="btn btn-warning w-full text-dark fw-bold rounded-3 py-2.5">
                        <i data-lucide="phone" class="me-1" style="width:16px; height:16px; vertical-align:middle; display:inline-block;"></i> Dial Support Instantly
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
