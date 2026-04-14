<!-- Cart -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel" style="background-color: var(--prm-blue); border-left: 1px solid var(--border-color); width: 400px;">
        
        <div class="offcanvas-header border-bottom" style="border-color: rgba(255,255,255,0.05) !important; padding: 1.5rem;">
            <h5 class="offcanvas-title text-white mb-0" id="cartOffcanvasLabel" style="font-family: 'Oswald', sans-serif; letter-spacing: 1px; font-size: 1.2rem;">
                Your Cart <span id="cartHeaderCount" class="text-gold ms-1">(0)</span>
            </h5>
            <button type="button" class="btn-close btn-close-white opacity-50 hover-opacity-100" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        
        <div class="offcanvas-body d-flex flex-column p-0 custom-scrollbar">
            
            <div id="freeShippingContainer" class="p-3 text-center" style="background: linear-gradient(180deg, rgba(212,175,55,0.05) 0%, transparent 100%);">
                <p id="freeShippingText" class="text-white mb-2" style="font-size: 0.8rem; font-weight: 500;">
                    You're <span class="text-gold fw-bold">LKR 5,000</span> away from Free Shipping!
                </p>
                <div class="progress" style="height: 6px; background-color: var(--input-bg); border-radius: 10px;">
                    <div id="freeShippingBar" class="progress-bar bg-gold" role="progressbar" style="width: 60%; border-radius: 10px; transition: width 0.4s ease;"></div>
                </div>
            </div>

            <div id="sideCartItems" class="flex-grow-1 overflow-auto p-3">
                </div>

            <div class="p-3 mt-auto border-top" style="border-color: rgba(255,255,255,0.05) !important; background-color: rgba(0,0,0,0.2);">
                <h6 class="text-white mb-3" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Complete Your Purchase</h6>
                
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded" style="background: var(--input-bg); border: 1px solid transparent; transition: 0.3s;" onmouseover="this.style.borderColor='var(--chp-gold)'" onmouseout="this.style.borderColor='transparent'">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-prm-blue d-flex align-items-center justify-content-center rounded" style="width: 40px; height: 40px;">
                            <i class="fas fa-gift text-gold"></i>
                        </div>
                        <div>
                            <p class="m-0 text-white fw-medium" style="font-size: 0.85rem;">Luxury Gift Box</p>
                            <span class="text-white" style="font-size: 0.75rem;">LKR 1,500</span>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-gold rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;" onclick="addAddonToCart('Luxury Gift Box', 1500)">Add</button>
                </div>
            </div>

            <div class="p-4 border-top" style="border-color: rgba(255,255,255,0.05) !important; background-color: var(--sec-blue);">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <span class="text-white" style="font-size: 0.9rem;">Subtotal</span>
                    <div class="text-end">
                        <span class="text-white fw-bold d-block" id="sideCartTotal" style="font-family: 'Oswald', sans-serif; font-size: 1.5rem; line-height: 1;">LKR 0.00</span>
                        <span class="text-white" style="font-size: 0.7rem;">Taxes and shipping calculated at checkout</span>
                    </div>
                </div>
                
                <button onclick="window.location.href='checkout.php'" class="btn btn-gold w-100 py-3 text-uppercase fw-bold shadow-sm" style="letter-spacing: 1px; border-radius: 6px;">
                    Checkout
                </button>
                
                <div class="text-center mt-3">
                    <p class="text-white m-0" style="font-size: 0.7rem;">
                        <i class="fas fa-lock me-1 text-gold"></i> Secure Encrypted Checkout
                    </p>
                </div>
            </div>
        </div>
    </div>