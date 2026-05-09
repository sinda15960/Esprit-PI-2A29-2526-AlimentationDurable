<?php if(isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
    <div class="account-deleted-message">
        <div class="deleted-alert">
            <span class="deleted-icon">👋</span>
            <div class="deleted-content">
                <strong>Your account has been successfully deleted!</strong>
                <p>We're sad to see you go. We hope to see you again soon!</p>
            </div>
            <button class="deleted-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
        </div>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['account_deleted'])): ?>
    <div class="account-deleted-message">
        <div class="deleted-alert">
            <span class="deleted-icon">👋</span>
            <div class="deleted-content">
                <strong><?php echo htmlspecialchars($_SESSION['account_deleted']); ?></strong>
                <p>We hope to see you again soon!</p>
            </div>
            <button class="deleted-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
        </div>
    </div>
    <?php unset($_SESSION['account_deleted']); ?>
<?php endif; ?>

<section class="hero">
    <div class="hero-container">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="badge-icon">✨</span>
                <span>AI-Powered Nutrition</span>
            </div>
            <h1 class="hero-title">
                <span class="title-white">HEALTHY</span>
                <span class="title-white">EAT</span>
                <span class="title-white">HEALTHY</span>
            </h1>
            <p class="hero-subtitle">plan your meals</p>
            <p class="hero-description">
                Discover smart, sustainable nutrition with AI-powered meal planning. 
                Get personalized recommendations based on your dietary needs.
            </p>
            <div class="hero-buttons">
                <a href="index.php?action=register" class="btn-primary">Start Your Journey</a>
                <a href="javascript:void(0)" class="btn-secondary" onclick="openLearnMoreModal()">Learn More</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="image-wrapper">
                <div class="floating-card card-1">
                    <span>🥑</span>
                    <p>Smart Meal Planning</p>
                </div>
                <div class="floating-card card-2">
                    <span>🌱</span>
                    <p>Sustainable Choices</p>
                </div>
                <div class="floating-card card-3">
                    <span>📊</span>
                    <p>AI Recommendations</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features-section" class="features">
    <div class="container">
        <h2 class="section-title">Why Choose NutriFlow AI?</h2>
        <div class="features-grid">
            <div class="feature-card" onclick="openFeatureModal('ai')">
                <div class="feature-icon">🧠</div>
                <h3>AI Intelligence</h3>
                <p>Personalized meal plans based on your preferences and goals</p>
            </div>
            <div class="feature-card" onclick="openFeatureModal('sustainable')">
                <div class="feature-icon">🌍</div>
                <h3>Sustainable</h3>
                <p>Eco-friendly food choices for a better planet</p>
            </div>
            <div class="feature-card" onclick="openFeatureModal('tracking')">
                <div class="feature-icon">📱</div>
                <h3>Easy Tracking</h3>
                <p>Monitor your nutrition progress effortlessly</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Showcase with Animated Counters -->
<section class="stats-showcase">
    <div class="container">
        <h2 class="section-title">📊 NutriFlow AI by the Numbers</h2>
        <p class="stats-subtitle">A growing community every day</p>
        <div class="stats-animated-grid">
            <div class="animated-stat">
                <div class="stat-icon-big">👥</div>
                <span class="animated-number" data-target="33479">0</span>
                <p>Active Users</p>
            </div>
            <div class="animated-stat">
                <div class="stat-icon-big">🍽️</div>
                <span class="animated-number" data-target="19015">0</span>
                <p>Meals Planned</p>
            </div>
            <div class="animated-stat">
                <div class="stat-icon-big">🌍</div>
                <span class="animated-number" data-target="4375">0</span>
                <p>kg CO₂ Saved</p>
            </div>
            <div class="animated-stat">
                <div class="stat-icon-big">⭐</div>
                <span class="animated-number" data-target="98">0</span>
                <span class="percent">%</span>
                <p>User Satisfaction</p>
            </div>
        </div>
    </div>
</section>

<!-- Interactive User Journey Section -->
<section class="journey-section">
    <div class="container">
        <h2 class="journey-title">🚀 Your Nutrition Journey</h2>
        <p class="journey-subtitle">Discover how NutriFlow AI transforms your daily life in 4 simple steps</p>
        
        <div class="journey-timeline">
            <div class="journey-step" data-step="1">
                <div class="step-number">1</div>
                <div class="step-icon">📝</div>
                <h3>Create Your Profile</h3>
                <p>Fill in your goals, allergies, and preferences</p>
                <div class="step-hover">✨ Only 2 minutes</div>
            </div>
            <div class="journey-arrow">→</div>
            <div class="journey-step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-icon">🤖</div>
                <h3>AI Analyzes Your Needs</h3>
                <p>Our algorithm generates personalized recommendations</p>
                <div class="step-hover">🧠 100% personalized</div>
            </div>
            <div class="journey-arrow">→</div>
            <div class="journey-step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-icon">🍽️</div>
                <h3>Receive Your Plans</h3>
                <p>Weekly menus and automatic shopping lists</p>
                <div class="step-hover">📋 Ready to use</div>
            </div>
            <div class="journey-arrow">→</div>
            <div class="journey-step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-icon">📈</div>
                <h3>Track Your Progress</h3>
                <p>Interactive dashboard and real-time adjustments</p>
                <div class="step-hover">🏆 Achieve your goals</div>
            </div>
        </div>
        
        <div class="journey-cta">
            <a href="index.php?action=register" class="btn-journey">Start My Journey →</a>
        </div>
    </div>
</section>

<!-- Testimonials Carousel -->
<section class="testimonials-section">
    <div class="container">
        <h2 class="section-title">💬 What Our Users Say</h2>
        <p class="testimonials-subtitle">Trusted by thousands of happy users</p>
        
        <div class="testimonials-carousel">
            <div class="testimonial-card active">
                <div class="testimonial-avatar">👩‍🦱</div>
                <p class="testimonial-text">"NutriFlow AI has completely changed the way I eat. The recommendations are always relevant and adapted to my goals!"</p>
                <div class="testimonial-author">
                    <strong>Marie Laurent</strong>
                    <span>User for 6 months</span>
                    <div class="rating">★★★★★</div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-avatar">👨‍🦰</div>
                <p class="testimonial-text">"I lost 12 kg in 3 months thanks to the personalized plans. The app is intuitive and the tracking is excellent."</p>
                <div class="testimonial-author">
                    <strong>Thomas Dubois</strong>
                    <span>Goal achieved</span>
                    <div class="rating">★★★★★</div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-avatar">👩‍🦳</div>
                <p class="testimonial-text">"Finally an app that takes into account my allergies and dietary preferences. I highly recommend it!"</p>
                <div class="testimonial-author">
                    <strong>Sophie Martin</strong>
                    <span>Gluten allergic</span>
                    <div class="rating">★★★★★</div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-avatar">👨‍🦱</div>
                <p class="testimonial-text">"The AI recommendations are incredibly accurate. I've never felt better about my nutrition choices!"</p>
                <div class="testimonial-author">
                    <strong>David Kim</strong>
                    <span>Fitness enthusiast</span>
                    <div class="rating">★★★★★</div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-avatar">👩‍🦰</div>
                <p class="testimonial-text">"As a busy professional, NutriFlow AI saves me hours of meal planning every week. Game changer!"</p>
                <div class="testimonial-author">
                    <strong>Emma Wilson</strong>
                    <span>Working mom</span>
                    <div class="rating">★★★★★</div>
                </div>
            </div>
        </div>
        <div class="carousel-dots">
            <span class="dot active" onclick="currentTestimonial(0)"></span>
            <span class="dot" onclick="currentTestimonial(1)"></span>
            <span class="dot" onclick="currentTestimonial(2)"></span>
            <span class="dot" onclick="currentTestimonial(3)"></span>
            <span class="dot" onclick="currentTestimonial(4)"></span>
        </div>
    </div>
</section>

<!-- Nutrition Quiz Section -->
<section class="quiz-section">
    <div class="container">
        <h2 class="section-title">🎯 What's Your Nutrition Profile?</h2>
        <p class="quiz-subtitle">Take this 30-second quiz and get personalized recommendations!</p>
        
        <div class="quiz-container" id="quizContainer">
            <div class="quiz-question active" data-question="1">
                <div class="question-header">
                    <span class="question-number">Question 1/5</span>
                    <span class="question-progress">20%</span>
                </div>
                <div class="progress-bar-quiz">
                    <div class="progress-fill" style="width: 20%"></div>
                </div>
                <h3 class="question-text">What is your main health goal?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="weight-loss">
                        <span class="option-icon">🏋️</span>
                        <div><strong>Lose Weight</strong><p>I want to shed some pounds</p></div>
                    </div>
                    <div class="quiz-option" data-value="muscle-gain">
                        <span class="option-icon">💪</span>
                        <div><strong>Build Muscle</strong><p>I want to gain muscle mass</p></div>
                    </div>
                    <div class="quiz-option" data-value="maintenance">
                        <span class="option-icon">⚖️</span>
                        <div><strong>Maintain Health</strong><p>I want to stay healthy</p></div>
                    </div>
                    <div class="quiz-option" data-value="energy">
                        <span class="option-icon">⚡</span>
                        <div><strong>Boost Energy</strong><p>I want more daily energy</p></div>
                    </div>
                </div>
            </div>

            <div class="quiz-question" data-question="2">
                <div class="question-header">
                    <span class="question-number">Question 2/5</span>
                    <span class="question-progress">40%</span>
                </div>
                <div class="progress-bar-quiz">
                    <div class="progress-fill" style="width: 40%"></div>
                </div>
                <h3 class="question-text">What's your dietary preference?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="omnivore"><span class="option-icon">🍖</span><div><strong>Omnivore</strong><p>I eat everything</p></div></div>
                    <div class="quiz-option" data-value="vegetarian"><span class="option-icon">🥦</span><div><strong>Vegetarian</strong><p>No meat, but eat dairy & eggs</p></div></div>
                    <div class="quiz-option" data-value="vegan"><span class="option-icon">🌱</span><div><strong>Vegan</strong><p>Plant-based only</p></div></div>
                    <div class="quiz-option" data-value="keto"><span class="option-icon">🥑</span><div><strong>Keto</strong><p>Low carb, high fat</p></div></div>
                </div>
            </div>

            <div class="quiz-question" data-question="3">
                <div class="question-header">
                    <span class="question-number">Question 3/5</span>
                    <span class="question-progress">60%</span>
                </div>
                <div class="progress-bar-quiz">
                    <div class="progress-fill" style="width: 60%"></div>
                </div>
                <h3 class="question-text">How active are you?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="sedentary"><span class="option-icon">🛋️</span><div><strong>Sedentary</strong><p>Little to no exercise</p></div></div>
                    <div class="quiz-option" data-value="light"><span class="option-icon">🚶</span><div><strong>Light Activity</strong><p>Exercise 1-2 times/week</p></div></div>
                    <div class="quiz-option" data-value="moderate"><span class="option-icon">🏃</span><div><strong>Moderate Activity</strong><p>Exercise 3-4 times/week</p></div></div>
                    <div class="quiz-option" data-value="very-active"><span class="option-icon">🏋️‍♂️</span><div><strong>Very Active</strong><p>Exercise 5+ times/week</p></div></div>
                </div>
            </div>

            <div class="quiz-question" data-question="4">
                <div class="question-header">
                    <span class="question-number">Question 4/5</span>
                    <span class="question-progress">80%</span>
                </div>
                <div class="progress-bar-quiz">
                    <div class="progress-fill" style="width: 80%"></div>
                </div>
                <h3 class="question-text">What's your biggest challenge?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="time"><span class="option-icon">⏰</span><div><strong>Lack of Time</strong><p>Too busy to cook healthy</p></div></div>
                    <div class="quiz-option" data-value="budget"><span class="option-icon">💰</span><div><strong>Budget Constraints</strong><p>Healthy food is expensive</p></div></div>
                    <div class="quiz-option" data-value="motivation"><span class="option-icon">😴</span><div><strong>Lack of Motivation</strong><p>Hard to stay consistent</p></div></div>
                    <div class="quiz-option" data-value="knowledge"><span class="option-icon">📚</span><div><strong>Lack of Knowledge</strong><p>Don't know what to eat</p></div></div>
                </div>
            </div>

            <div class="quiz-question" data-question="5">
                <div class="question-header">
                    <span class="question-number">Question 5/5</span>
                    <span class="question-progress">100%</span>
                </div>
                <div class="progress-bar-quiz">
                    <div class="progress-fill" style="width: 100%"></div>
                </div>
                <h3 class="question-text">Do you have any dietary restrictions?</h3>
                <div class="quiz-options">
                    <div class="quiz-option" data-value="none"><span class="option-icon">✅</span><div><strong>No restrictions</strong><p>I can eat anything</p></div></div>
                    <div class="quiz-option" data-value="gluten-free"><span class="option-icon">🚫🌾</span><div><strong>Gluten Free</strong><p>Cannot eat gluten</p></div></div>
                    <div class="quiz-option" data-value="dairy-free"><span class="option-icon">🚫🥛</span><div><strong>Dairy Free</strong><p>Cannot eat dairy</p></div></div>
                    <div class="quiz-option" data-value="nut-free"><span class="option-icon">🚫🥜</span><div><strong>Nut Free</strong><p>Allergic to nuts</p></div></div>
                </div>
            </div>

            <div class="quiz-navigation">
                <button class="btn-prev" onclick="prevQuestion()" style="visibility: hidden;">← Previous</button>
                <button class="btn-next" onclick="nextQuestion()">Next →</button>
            </div>

            <div class="quiz-result" id="quizResult">
                <div class="result-icon" id="resultIcon">🏆</div>
                <h3 id="resultTitle">Your Nutrition Profile</h3>
                <p id="resultDescription"></p>
                <div class="result-recommendations" id="resultRecommendations"></div>
                <div class="result-actions">
                    <button class="btn-restart-quiz" onclick="restartQuiz()">⟳ Take Quiz Again</button>
                    <a href="index.php?action=register" class="btn-result-register">Get Your Personal Plan →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Global Impact Map Section with 3D Globe -->
<section class="impact-section">
    <div class="container">
        <h2 class="section-title">🌍 Our Global Impact</h2>
        <p class="impact-subtitle">Making healthy eating accessible worldwide</p>
        
        <div class="impact-container">
            <div class="globe-container">
                <div class="globe">
                    <div class="globe-inner">
                        <div class="globe-shadow"></div>
                        <div class="globe-pin pin-na"><div class="pin-dot-red"></div><div class="pin-pulse-red"></div><div class="pin-tooltip-globe"><h4>North America</h4><p>USA | Canada | Mexico</p><div class="pin-stats"><span>👥 8,234 users</span><span>🍽️ 4,521 meals</span></div></div></div>
                        <div class="globe-pin pin-sa"><div class="pin-dot-red"></div><div class="pin-pulse-red"></div><div class="pin-tooltip-globe"><h4>South America</h4><p>Brazil | Argentina | Chile</p><div class="pin-stats"><span>👥 3,421 users</span><span>🍽️ 1,892 meals</span></div></div></div>
                        <div class="globe-pin pin-eu"><div class="pin-dot-red"></div><div class="pin-pulse-red"></div><div class="pin-tooltip-globe"><h4>Europe</h4><p>France | Germany | UK | Spain</p><div class="pin-stats"><span>👥 12,567 users</span><span>🍽️ 7,234 meals</span></div></div></div>
                        <div class="globe-pin pin-af"><div class="pin-dot-red"></div><div class="pin-pulse-red"></div><div class="pin-tooltip-globe"><h4>Africa</h4><p>South Africa | Nigeria | Kenya</p><div class="pin-stats"><span>👥 1,234 users</span><span>🍽️ 678 meals</span></div></div></div>
                        <div class="globe-pin pin-as"><div class="pin-dot-red"></div><div class="pin-pulse-red"></div><div class="pin-tooltip-globe"><h4>Asia</h4><p>Japan | China | India | Korea</p><div class="pin-stats"><span>👥 5,678 users</span><span>🍽️ 3,456 meals</span></div></div></div>
                        <div class="globe-pin pin-au"><div class="pin-dot-red"></div><div class="pin-pulse-red"></div><div class="pin-tooltip-globe"><h4>Australia</h4><p>Australia | New Zealand</p><div class="pin-stats"><span>👥 2,345 users</span><span>🍽️ 1,234 meals</span></div></div></div>
                    </div>
                </div>
            </div>
            
            <div class="impact-stats-sidebar">
                <div class="impact-stat-global"><div class="global-icon">🌍</div><div class="global-number">33,479</div><div class="global-label">Total Users Worldwide</div></div>
                <div class="impact-stat-global"><div class="global-icon">🍽️</div><div class="global-number">19,015</div><div class="global-label">Meals Planned</div></div>
                <div class="impact-stat-global"><div class="global-icon">🌱</div><div class="global-number">4,375</div><div class="global-label">kg CO₂ Saved</div></div>
                <div class="continent-stats"><h4>🌐 Continent Breakdown</h4>
                    <div class="continent-bar"><span class="continent-name">Europe</span><div class="bar-container"><div class="bar-fill" style="width: 38%"></div></div><span class="continent-percent">38%</span></div>
                    <div class="continent-bar"><span class="continent-name">North America</span><div class="bar-container"><div class="bar-fill" style="width: 25%"></div></div><span class="continent-percent">25%</span></div>
                    <div class="continent-bar"><span class="continent-name">Asia</span><div class="bar-container"><div class="bar-fill" style="width: 17%"></div></div><span class="continent-percent">17%</span></div>
                    <div class="continent-bar"><span class="continent-name">South America</span><div class="bar-container"><div class="bar-fill" style="width: 10%"></div></div><span class="continent-percent">10%</span></div>
                    <div class="continent-bar"><span class="continent-name">Australia</span><div class="bar-container"><div class="bar-fill" style="width: 7%"></div></div><span class="continent-percent">7%</span></div>
                    <div class="continent-bar"><span class="continent-name">Africa</span><div class="bar-container"><div class="bar-fill" style="width: 3%"></div></div><span class="continent-percent">3%</span></div>
                </div>
            </div>
        </div>
        
        <div class="impact-highlights">
            <div class="highlight-card"><span class="highlight-icon">🏆</span><div><h4>#1 AI Nutrition Platform</h4><p>Ranked top in user satisfaction</p></div></div>
            <div class="highlight-card"><span class="highlight-icon">🌿</span><div><h4>4,375 kg CO₂ Saved</h4><p>Equivalent to planting 200 trees</p></div></div>
            <div class="highlight-card"><span class="highlight-icon">⭐</span><div><h4>4.9/5 Average Rating</h4><p>From 5,000+ reviews worldwide</p></div></div>
        </div>
    </div>
</section>

<!-- Before/After Transformation Section -->
<section class="transformation-section">
    <div class="container">
        <h2 class="section-title">🔄 See the Transformation</h2>
        <p class="transformation-subtitle">Real results from real NutriFlow AI users</p>
        
        <div class="beforeafter-container">
            <div class="beforeafter-card">
                <div class="beforeafter-slider" id="slider1">
                    <div class="beforeafter-images">
                        <div class="before-image"><div class="before-placeholder"><span class="placeholder-icon">🍔</span><p class="placeholder-title">Before NutriFlow AI</p><div class="placeholder-list"><span>❌ No meal planning</span><span>❌ Unhealthy choices</span><span>❌ No progress tracking</span></div></div><div class="before-label">BEFORE</div></div>
                        <div class="after-image"><div class="after-placeholder"><span class="placeholder-icon">🥗</span><p class="placeholder-title">After NutriFlow AI</p><div class="placeholder-list"><span>✅ Smart meal planning</span><span>✅ Healthy choices</span><span>✅ Progress tracking</span></div></div><div class="after-label">AFTER</div></div>
                        <div class="slider-handle"><div class="slider-line"></div><div class="slider-circle"><span>◀</span><span>▶</span></div></div>
                    </div>
                </div>
                <div class="beforeafter-stats"><div class="stat-item"><span class="stat-change positive">-15 kg</span><span class="stat-label">Weight Lost</span></div><div class="stat-item"><span class="stat-change positive">+32%</span><span class="stat-label">Energy Level</span></div><div class="stat-item"><span class="stat-change positive">-40%</span><span class="stat-label">Body Fat</span></div></div>
                <p class="testimonial-small">"NutriFlow AI helped me transform my life in just 3 months!"</p><p class="testimonial-author-small">— Michael Chen, Verified User</p>
            </div>

            <div class="beforeafter-card">
                <div class="beforeafter-slider" id="slider2">
                    <div class="beforeafter-images">
                        <div class="before-image"><div class="before-placeholder second-before"><span class="placeholder-icon">📱</span><p class="placeholder-title">Before NutriFlow AI</p><div class="placeholder-list"><span>❌ Random eating</span><span>❌ No structure</span><span>❌ Low energy</span></div></div><div class="before-label">BEFORE</div></div>
                        <div class="after-image"><div class="after-placeholder second-after"><span class="placeholder-icon">🏆</span><p class="placeholder-title">After NutriFlow AI</p><div class="placeholder-list"><span>✅ Structured meals</span><span>✅ Balanced nutrition</span><span>✅ High energy</span></div></div><div class="after-label">AFTER</div></div>
                        <div class="slider-handle"><div class="slider-line"></div><div class="slider-circle"><span>◀</span><span>▶</span></div></div>
                    </div>
                </div>
                <div class="beforeafter-stats"><div class="stat-item"><span class="stat-change positive">-22 kg</span><span class="stat-label">Weight Lost</span></div><div class="stat-item"><span class="stat-change positive">+45%</span><span class="stat-label">Strength Gain</span></div><div class="stat-item"><span class="stat-change positive">-35%</span><span class="stat-label">BMI Reduction</span></div></div>
                <p class="testimonial-small">"I've never felt better! NutriFlow AI changed everything for me."</p><p class="testimonial-author-small">— Sarah Johnson, Verified User</p>
            </div>
        </div>
    </div>
</section>

<!-- Project Timeline Section -->
<section class="timeline-section">
    <div class="container">
        <h2 class="section-title">📅 Our Journey</h2>
        <p class="timeline-subtitle">The evolution of NutriFlow AI - From concept to reality</p>
        
        <div class="timeline">
            <div class="timeline-item left"><div class="timeline-badge"><span class="badge-icon">💡</span></div><div class="timeline-content"><div class="timeline-date">January 2024</div><h3>The Idea</h3><p>Birth of NutriFlow AI - A vision to make healthy eating accessible through artificial intelligence.</p><div class="timeline-stats"><span>🎯 Concept validated</span><span>📝 Market research completed</span></div></div></div>
            <div class="timeline-item right"><div class="timeline-badge"><span class="badge-icon">⚙️</span></div><div class="timeline-content"><div class="timeline-date">March 2024</div><h3>Development Begins</h3><p>Started building the core platform - User authentication, database design, and basic features.</p><div class="timeline-stats"><span>💻 1,000+ lines of code</span><span>🗄️ MySQL database setup</span></div></div></div>
            <div class="timeline-item left"><div class="timeline-badge"><span class="badge-icon">🤖</span></div><div class="timeline-content"><div class="timeline-date">June 2024</div><h3>AI Integration</h3><p>Integrated ChatGPT-4 API for personalized meal recommendations and nutrition analysis.</p><div class="timeline-stats"><span>🧠 98% recommendation accuracy</span><span>📊 10,000+ meals analyzed</span></div></div></div>
            <div class="timeline-item right"><div class="timeline-badge"><span class="badge-icon">🎨</span></div><div class="timeline-content"><div class="timeline-date">September 2024</div><h3>UI/UX Redesign</h3><p>Complete redesign with modern interface, animations, and mobile-responsive layout.</p><div class="timeline-stats"><span>📱 Fully responsive</span><span>✨ 15+ interactive components</span></div></div></div>
            <div class="timeline-item left"><div class="timeline-badge"><span class="badge-icon">🚀</span></div><div class="timeline-content"><div class="timeline-date">December 2024</div><h3>Beta Launch</h3><p>Public beta release with 500+ active users testing the platform.</p><div class="timeline-stats"><span>👥 500+ beta testers</span><span>⭐ 4.9/5 user rating</span></div></div></div>
            <div class="timeline-item right"><div class="timeline-badge"><span class="badge-icon">🏆</span></div><div class="timeline-content"><div class="timeline-date">Today</div><h3>Official Launch</h3><p>Full platform release with all features - Ready to transform nutrition worldwide!</p><div class="timeline-stats"><span>🌍 33,000+ active users</span><span>🍽️ 19,000+ meals planned</span></div></div></div>
        </div>
        
        <div class="timeline-milestones">
            <div class="milestone"><span class="milestone-number">12</span><span class="milestone-label">Months of Development</span></div>
            <div class="milestone"><span class="milestone-number">15K+</span><span class="milestone-label">Lines of Code</span></div>
            <div class="milestone"><span class="milestone-number">100%</span><span class="milestone-label">Student Passion</span></div>
        </div>
    </div>
</section>

<!-- Modals -->
<div id="modal-ai" class="feature-modal"><div class="feature-modal-content"><div class="feature-modal-header ai-header"><span class="feature-modal-icon">🧠</span><h2>AI Intelligence</h2><span class="feature-modal-close" onclick="closeFeatureModal('ai')">&times;</span></div><div class="feature-modal-body"><div class="feature-stats"><div class="stat-bubble"><span class="stat-number">98%</span><span class="stat-label">Accuracy</span></div><div class="stat-bubble"><span class="stat-number">10K+</span><span class="stat-label">Meals Analyzed</span></div><div class="stat-bubble"><span class="stat-number">500+</span><span class="stat-label">Recipes Generated</span></div></div><div class="feature-description"><h3>How does AI work for you?</h3><p>Our advanced machine learning algorithm analyzes your dietary preferences, health goals, allergies, and eating habits to create personalized meal plans.</p><div class="ai-badge">🤖 Powered by ChatGPT-4 & Custom Nutrition Models</div></div><div class="feature-tip"><span class="tip-icon">💡</span><p><strong>Pro Tip:</strong> The more you use NutriFlow AI, the smarter your recommendations become!</p></div></div><div class="feature-modal-footer"><button class="btn-feature" onclick="closeFeatureModal('ai')">Got it!</button><a href="index.php?action=register" class="btn-feature-primary">Try AI Now →</a></div></div></div>

<div id="modal-sustainable" class="feature-modal"><div class="feature-modal-content"><div class="feature-modal-header sustainable-header"><span class="feature-modal-icon">🌍</span><h2>Sustainable Choices</h2><span class="feature-modal-close" onclick="closeFeatureModal('sustainable')">&times;</span></div><div class="feature-modal-body"><div class="feature-stats"><div class="stat-bubble"><span class="stat-number">156kg</span><span class="stat-label">CO₂ Saved</span></div><div class="stat-bubble"><span class="stat-number">2,340L</span><span class="stat-label">Water Saved</span></div><div class="stat-bubble"><span class="stat-number">89%</span><span class="stat-label">User Satisfaction</span></div></div><div class="feature-description"><h3>Eat Better for the Planet</h3><p>Track your carbon footprint, get seasonal recommendations, reduce food waste, and choose sustainable options.</p><div class="eco-badge">🌟 You've helped save 156kg of CO₂ already!</div></div><div class="feature-tip"><span class="tip-icon">🌿</span><p><strong>Did you know?</strong> Reducing meat consumption by just 1 day/week can save 160kg of CO₂ per year!</p></div></div><div class="feature-modal-footer"><button class="btn-feature" onclick="closeFeatureModal('sustainable')">Got it!</button><a href="index.php?action=register" class="btn-feature-primary">Start Your Eco-Journey →</a></div></div></div>

<div id="modal-tracking" class="feature-modal"><div class="feature-modal-content"><div class="feature-modal-header tracking-header"><span class="feature-modal-icon">📱</span><h2>Easy Tracking</h2><span class="feature-modal-close" onclick="closeFeatureModal('tracking')">&times;</span></div><div class="feature-modal-body"><div class="feature-stats"><div class="stat-bubble"><span class="stat-number">30s</span><span class="stat-label">Daily Log</span></div><div class="stat-bubble"><span class="stat-number">24/7</span><span class="stat-label">Access</span></div><div class="stat-bubble"><span class="stat-number">100%</span><span class="stat-label">Privacy</span></div></div><div class="feature-description"><h3>Track Your Progress Seamlessly</h3><p>Interactive dashboard, meal photo recognition, progress charts, and smart reminders all in one place.</p><div class="tracking-badge">🏆 Top 10% of users who reached their goals this month!</div></div><div class="feature-tip"><span class="tip-icon">⏰</span><p><strong>Pro Tip:</strong> Set daily reminders to log your meals - consistency is key to success!</p></div></div><div class="feature-modal-footer"><button class="btn-feature" onclick="closeFeatureModal('tracking')">Got it!</button><a href="index.php?action=register" class="btn-feature-primary">Start Tracking →</a></div></div></div>

<div id="modal-learnmore" class="learnmore-modal"><div class="learnmore-modal-content"><div class="learnmore-modal-header"><span class="learnmore-modal-close" onclick="closeLearnMoreModal()">&times;</span><div class="learnmore-logo"><span class="learnmore-logo-icon">🥗</span><h2>NutriFlow AI</h2></div><p class="learnmore-tagline">"Smart Nutrition for a Better Tomorrow"</p></div><div class="learnmore-modal-body"><div class="learnmore-vision"><h3>🌟 Our Vision</h3><p>We believe that healthy eating should be accessible, personalized, and sustainable for everyone. NutriFlow AI combines cutting-edge artificial intelligence with nutritional science to transform how people approach their daily meals.</p></div><div class="learnmore-stats"><div class="learnmore-stat-card"><span class="stat-emoji">🚀</span><span class="stat-number">1,000+</span><span class="stat-label">Active Users</span></div><div class="learnmore-stat-card"><span class="stat-emoji">🍽️</span><span class="stat-number">5,000+</span><span class="stat-label">Meals Planned</span></div><div class="learnmore-stat-card"><span class="stat-emoji">⭐</span><span class="stat-number">4.9/5</span><span class="stat-label">User Rating</span></div></div><div class="learnmore-values"><h3>💚 Our Core Values</h3><div class="values-grid"><div class="value-item"><span>🤝</span><div><strong>Accessibility</strong><p>Nutrition for all budgets</p></div></div><div class="value-item"><span>🌱</span><div><strong>Sustainability</strong><p>Eco-friendly choices</p></div></div><div class="value-item"><span>🔬</span><div><strong>Science-Backed</strong><p>Evidence-based nutrition</p></div></div><div class="value-item"><span>❤️</span><div><strong>Community</strong><p>Supportive food lovers</p></div></div></div></div><div class="learnmore-quote"><span class="quote-icon">💬</span><p>"NutriFlow AI changed the way I eat. The personalized recommendations are incredibly accurate!"</p><span class="quote-author">— Sarah M., Verified User</span></div><div class="learnmore-cta"><p>Ready to transform your nutrition journey?</p><div class="learnmore-buttons"><a href="index.php?action=register" class="btn-learnmore-primary">Start Your Journey 🚀</a><a href="index.php?action=login" class="btn-learnmore-secondary">Sign In →</a></div></div></div></div></div>

<style>
/* Reset & Base */
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#16a34a 0%,#14532d 100%);color:#333;line-height:1.6}

/* Hero Section */
.hero{min-height:auto;display:flex;align-items:center;padding:5rem 2rem 3rem}
.hero-container{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center}
.hero-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.2);padding:.5rem 1rem;border-radius:50px;backdrop-filter:blur(10px);margin-bottom:1.5rem;color:#fff}
.hero-title{font-size:3.5rem;font-weight:800;line-height:1.2;margin-bottom:.5rem}
.title-white{color:#fff;display:block;text-transform:uppercase}
.hero-subtitle{font-size:1.2rem;color:#e2e8f0;margin-bottom:1rem;font-weight:600}
.hero-description{color:#e2e8f0;margin-bottom:1.5rem;line-height:1.6}
.hero-buttons{display:flex;gap:1rem}
.btn-primary{padding:.8rem 1.8rem;border-radius:50px;text-decoration:none;font-weight:600;transition:all .3s;background:#fff;color:#16a34a}
.btn-primary:hover{background:#16a34a;color:#fff;transform:translateY(-2px);box-shadow:0 10px 20px rgba(0,0,0,.2)}
.btn-secondary{padding:.8rem 1.8rem;border-radius:50px;text-decoration:none;font-weight:600;transition:all .3s;background:transparent;color:#fff;border:2px solid #fff}
.btn-secondary:hover{background:#fff;color:#16a34a;transform:translateY(-2px)}
.hero-image{position:relative}
.image-wrapper{position:relative;height:350px}
.floating-card{position:absolute;background:#fff;padding:.8rem 1.2rem;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.15);display:flex;align-items:center;gap:.5rem;animation:float 3s ease-in-out infinite}
.floating-card span{font-size:1.3rem}
.card-1{top:10%;left:0;animation-delay:0s}
.card-2{top:40%;right:0;animation-delay:1s}
.card-3{bottom:15%;left:15%;animation-delay:2s}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-15px)}}

/* Features Section */
.features{background:#ecfdf5;padding:4rem 2rem}
.container{max-width:1200px;margin:0 auto}
.section-title{text-align:center;font-size:2rem;margin-bottom:2.5rem;background:linear-gradient(135deg,#16a34a,#14532d);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:2rem}
.feature-card{text-align:center;padding:1.8rem;border-radius:15px;transition:all .3s;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.05);cursor:pointer}
.feature-card:hover{transform:translateY(-10px);box-shadow:0 15px 35px rgba(0,0,0,.15);border-bottom:3px solid #16a34a}
.feature-icon{font-size:2.5rem;margin-bottom:1rem}
.feature-card h3{font-size:1.2rem;margin-bottom:.5rem;color:#2d3748}
.feature-card p{font-size:.9rem;color:#718096}

/* Feature Modals */
.feature-modal{display:none;position:fixed;z-index:2000;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.7);animation:fadeIn .3s ease}
.feature-modal-content{background:#fff;margin:5% auto;width:90%;max-width:600px;border-radius:24px;animation:slideUp .4s ease;overflow:hidden}
@keyframes slideUp{from{opacity:0;transform:translateY(50px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
.feature-modal-header{padding:1.5rem;display:flex;align-items:center;gap:1rem;color:#fff}
.ai-header{background:linear-gradient(135deg,#6366f1,#8b5cf6)}
.sustainable-header{background:linear-gradient(135deg,#059669,#10b981)}
.tracking-header{background:linear-gradient(135deg,#f59e0b,#f97316)}
.feature-modal-icon{font-size:2rem}
.feature-modal-header h2{flex:1;margin:0;font-size:1.5rem}
.feature-modal-close{font-size:2rem;cursor:pointer;transition:opacity .3s}
.feature-modal-close:hover{opacity:.7}
.feature-modal-body{padding:1.5rem}
.feature-stats{display:flex;justify-content:space-around;gap:1rem;margin-bottom:1.5rem}
.stat-bubble{text-align:center;padding:1rem;background:#f8fafc;border-radius:16px;flex:1}
.stat-number{display:block;font-size:1.5rem;font-weight:800;color:#1a202c}
.stat-label{font-size:.7rem;color:#718096}
.feature-description h3{color:#1a202c;font-size:1.1rem;margin-bottom:.75rem}
.feature-description p{color:#4a5568;margin-bottom:1rem;font-size:.9rem}
.ai-badge,.eco-badge,.tracking-badge{background:linear-gradient(135deg,#f0fdf4,#dcfce7);padding:.75rem;border-radius:12px;margin:1rem 0;text-align:center;font-size:.85rem;font-weight:500;color:#166534}
.feature-tip{background:#fffbeb;border-left:4px solid #f59e0b;padding:.75rem 1rem;border-radius:12px;display:flex;gap:.75rem;margin-top:1rem}
.tip-icon{font-size:1.25rem}
.feature-tip p{margin:0;font-size:.85rem;color:#78350f}
.feature-modal-footer{padding:1rem 1.5rem 1.5rem;display:flex;gap:1rem;justify-content:flex-end}
.btn-feature{padding:.6rem 1.2rem;background:#e2e8f0;border:none;border-radius:10px;cursor:pointer;font-weight:500}
.btn-feature:hover{background:#cbd5e0}
.btn-feature-primary{padding:.6rem 1.2rem;background:linear-gradient(135deg,#16a34a,#14532d);color:#fff;text-decoration:none;border-radius:10px;font-weight:600}
.btn-feature-primary:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(22,163,74,.3)}

/* Stats Showcase */
.stats-showcase{background:linear-gradient(135deg,#f0fdf4,#ecfdf5);padding:4rem 2rem;text-align:center}
.stats-subtitle{color:#166534;margin-bottom:3rem;font-size:1.1rem}
.stats-animated-grid{display:flex;justify-content:center;gap:2rem;flex-wrap:wrap}
.animated-stat{text-align:center;padding:2rem;background:#fff;border-radius:30px;min-width:200px;transition:all .3s;box-shadow:0 10px 30px rgba(0,0,0,.05)}
.animated-stat:hover{transform:translateY(-10px);box-shadow:0 20px 40px rgba(22,163,74,.15)}
.stat-icon-big{font-size:2.5rem;margin-bottom:1rem}
.animated-number{font-size:3rem;font-weight:800;color:#16a34a;display:inline-block}
.animated-stat p{color:#166534;font-weight:500;margin-top:.5rem}
.percent{font-size:2rem;font-weight:800;color:#16a34a}

/* Journey Section */
.journey-section{background:linear-gradient(135deg,#0f172a,#1e293b);padding:5rem 2rem}
.journey-title{text-align:center;font-size:2rem;margin-bottom:1rem;color:#fff}
.journey-subtitle{text-align:center;color:#94a3b8;margin-bottom:4rem}
.journey-timeline{display:flex;justify-content:center;align-items:center;flex-wrap:wrap;gap:1rem;max-width:1200px;margin:0 auto}
.journey-step{background:rgba(255,255,255,.1);backdrop-filter:blur(10px);padding:2rem 1.5rem;border-radius:24px;text-align:center;width:220px;transition:all .4s cubic-bezier(.175,.885,.32,1.275);cursor:pointer;position:relative;overflow:hidden}
.journey-step::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);transition:left .5s}
.journey-step:hover::before{left:100%}
.journey-step:hover{transform:translateY(-15px) scale(1.05);background:linear-gradient(135deg,#16a34a,#14532d);box-shadow:0 25px 40px rgba(22,163,74,.3)}
.step-number{position:absolute;top:-10px;left:-10px;width:40px;height:40px;background:#16a34a;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.2rem;color:#fff}
.step-icon{font-size:3rem;margin-bottom:1rem}
.journey-step h3{font-size:1.2rem;margin-bottom:.75rem;color:#fff}
.journey-step p{font-size:.85rem;color:#cbd5e0}
.step-hover{opacity:0;transform:translateY(10px);transition:all .3s;margin-top:1rem;font-size:.8rem;color:#facc15}
.journey-step:hover .step-hover{opacity:1;transform:translateY(0)}
.journey-arrow{font-size:2rem;color:#16a34a;font-weight:700;animation:pulseArrow 1.5s infinite}
@keyframes pulseArrow{0%,100%{opacity:1;transform:translateX(0)}50%{opacity:.5;transform:translateX(5px)}}
.journey-cta{text-align:center;margin-top:4rem}
.btn-journey{display:inline-block;padding:1rem 2.5rem;background:linear-gradient(135deg,#16a34a,#14532d);color:#fff;text-decoration:none;border-radius:50px;font-weight:600;transition:all .3s;animation:glow 2s infinite}
.btn-journey:hover{transform:scale(1.05);box-shadow:0 10px 30px rgba(22,163,74,.5)}
@keyframes glow{0%,100%{box-shadow:0 0 5px #16a34a}50%{box-shadow:0 0 20px #16a34a}}

/* Testimonials Section */
.testimonials-section{background:#fff;padding:4rem 2rem;text-align:center}
.testimonials-subtitle{color:#718096;margin-bottom:3rem}
.testimonials-carousel{max-width:800px;margin:0 auto}
.testimonial-card{display:none;background:#f8fafc;padding:2rem;border-radius:30px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.05)}
.testimonial-card.active{display:block;animation:fadeInScale .5s ease}
@keyframes fadeInScale{from{opacity:0;transform:scale(.9)}to{opacity:1;transform:scale(1)}}
.testimonial-avatar{font-size:4rem;margin-bottom:1rem}
.testimonial-text{font-size:1.1rem;color:#2d3748;font-style:italic;line-height:1.6;margin-bottom:1.5rem}
.testimonial-author strong{display:block;color:#1a202c;font-size:1rem}
.testimonial-author span{font-size:.8rem;color:#718096}
.rating{margin-top:.5rem;color:#f59e0b;letter-spacing:2px}
.carousel-dots{margin-top:2rem}
.dot{display:inline-block;width:12px;height:12px;border-radius:50%;background:#cbd5e0;margin:0 5px;cursor:pointer;transition:all .3s}
.dot.active{background:#16a34a;width:30px;border-radius:10px}
.dot:hover{background:#16a34a}

/* Quiz Section */
.quiz-section{background:linear-gradient(135deg,#f8fafc,#ecfdf5);padding:5rem 2rem}
.quiz-subtitle{text-align:center;color:#718096;margin-bottom:3rem}
.quiz-container{max-width:700px;margin:0 auto;background:#fff;border-radius:32px;padding:2rem;box-shadow:0 20px 40px rgba(0,0,0,.1);min-height:500px}
.quiz-question{display:none;animation:fadeInQuiz .5s ease}
.quiz-question.active{display:block}
@keyframes fadeInQuiz{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}
.question-header{display:flex;justify-content:space-between;margin-bottom:1rem;color:#16a34a;font-weight:600;font-size:.9rem}
.progress-bar-quiz{height:8px;background:#e2e8f0;border-radius:10px;margin-bottom:2rem;overflow:hidden}
.progress-fill{height:100%;background:linear-gradient(90deg,#16a34a,#14532d);border-radius:10px;transition:width .3s ease}
.question-text{font-size:1.5rem;color:#1a202c;margin-bottom:2rem;text-align:center}
.quiz-options{display:flex;flex-direction:column;gap:1rem}
.quiz-option{display:flex;align-items:center;gap:1.2rem;padding:1.2rem;background:#f8fafc;border-radius:20px;cursor:pointer;transition:all .3s;border:2px solid transparent}
.quiz-option:hover{background:#ecfdf5;transform:translateX(8px);border-color:#16a34a}
.quiz-option.selected{background:linear-gradient(135deg,#dcfce7,#ecfdf5);border-color:#16a34a;box-shadow:0 5px 15px rgba(22,163,74,.2)}
.option-icon{font-size:2rem;min-width:50px;text-align:center}
.quiz-option strong{display:block;color:#1a202c;font-size:1rem}
.quiz-option p{margin:0;font-size:.8rem;color:#718096}
.quiz-navigation{display:flex;justify-content:space-between;margin-top:2rem;gap:1rem}
.btn-prev,.btn-next{padding:.8rem 1.5rem;border-radius:50px;font-weight:600;cursor:pointer;border:none}
.btn-prev{background:#e2e8f0;color:#4a5568}
.btn-prev:hover{background:#cbd5e0}
.btn-next{background:linear-gradient(135deg,#16a34a,#14532d);color:#fff}
.btn-next:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(22,163,74,.3)}
.btn-next:disabled{opacity:.5;cursor:not-allowed}
.quiz-result{display:none;text-align:center;animation:fadeInQuiz .5s ease}
.quiz-result.active{display:block}
.result-icon{font-size:4rem;margin-bottom:1rem}
.result-icon.winner{animation:bounce .6s ease}
@keyframes bounce{0%,100%{transform:scale(1)}50%{transform:scale(1.2)}}
#resultTitle{font-size:1.8rem;color:#1a202c;margin-bottom:1rem}
#resultDescription{color:#4a5568;margin-bottom:1.5rem}
.result-recommendations{background:#f0fdf4;padding:1.5rem;border-radius:20px;margin-bottom:2rem;text-align:left}
.result-recommendations h4{color:#16a34a;margin-bottom:1rem}
.result-recommendations ul{list-style:none;padding:0}
.result-recommendations li{padding:.5rem 0;color:#2d3748;display:flex;align-items:center;gap:.5rem;border-bottom:1px solid #dcfce7}
.result-actions{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap}
.btn-restart-quiz{padding:.8rem 1.5rem;background:#e2e8f0;border:none;border-radius:50px;font-weight:600;cursor:pointer}
.btn-restart-quiz:hover{background:#cbd5e0;transform:translateY(-2px)}
.btn-result-register{padding:.8rem 1.8rem;background:linear-gradient(135deg,#16a34a,#14532d);color:#fff;text-decoration:none;border-radius:50px;font-weight:600}
.btn-result-register:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(22,163,74,.3)}

/* Global Impact Section */
.impact-section{background:linear-gradient(135deg,#0f172a,#1e293b);padding:5rem 2rem}
.impact-subtitle{text-align:center;color:#94a3b8;margin-bottom:3rem}
.impact-container{display:grid;grid-template-columns:1fr 300px;gap:2rem;margin-bottom:3rem}
.globe-container{display:flex;justify-content:center;align-items:center;background:rgba(255,255,255,.03);border-radius:24px;padding:1rem}
.globe{width:100%;max-width:450px;aspect-ratio:1;position:relative;border-radius:50%;background:radial-gradient(circle at 30% 30%,#1e3a5f,#0f172a);box-shadow:0 0 20px rgba(0,0,0,.3),inset 0 0 20px rgba(255,255,255,.1);overflow:hidden;animation:globeFloat 6s ease-in-out infinite}
@keyframes globeFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.globe-inner{position:relative;width:100%;height:100%;border-radius:50%;overflow:hidden;background:radial-gradient(circle at 25% 35%,#2a4a7a,#0a1622);box-shadow:inset 0 0 50px rgba(0,0,0,.5)}
.globe-inner::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:radial-gradient(ellipse 60px 50px at 25% 30%,rgba(34,197,94,.15) 0%,transparent 70%),radial-gradient(ellipse 40px 60px at 32% 55%,rgba(34,197,94,.12) 0%,transparent 70%),radial-gradient(ellipse 35px 30px at 55% 25%,rgba(34,197,94,.15) 0%,transparent 70%),radial-gradient(ellipse 40px 50px at 52% 45%,rgba(34,197,94,.12) 0%,transparent 70%),radial-gradient(ellipse 70px 45px at 70% 30%,rgba(34,197,94,.15) 0%,transparent 70%),radial-gradient(ellipse 30px 25px at 78% 70%,rgba(34,197,94,.12) 0%,transparent 70%);border-radius:50%}
.globe-shadow{position:absolute;bottom:-20px;left:50%;transform:translateX(-50%);width:80%;height:20px;background:radial-gradient(ellipse,rgba(0,0,0,.3),transparent);border-radius:50%;filter:blur(5px)}
.globe-pin{position:absolute;cursor:pointer}
.pin-na{top:28%;left:22%}.pin-sa{top:52%;left:30%}.pin-eu{top:20%;left:52%}.pin-af{top:42%;left:50%}.pin-as{top:25%;left:68%}.pin-au{top:68%;left:76%}
.pin-dot-red{width:12px;height:12px;background:#ef4444;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px rgba(239,68,68,.5);transition:transform .3s}
.globe-pin:hover .pin-dot-red{transform:scale(1.3)}
.pin-pulse-red{position:absolute;top:-8px;left:-8px;width:28px;height:28px;background:rgba(239,68,68,.4);border-radius:50%;animation:pulseRed 1.5s infinite}
@keyframes pulseRed{0%{transform:scale(1);opacity:.8}70%{transform:scale(1.8);opacity:0}100%{transform:scale(1);opacity:0}}
.pin-tooltip-globe{position:absolute;bottom:100%;left:50%;transform:translateX(-50%);background:#fff;padding:.5rem .75rem;border-radius:10px;min-width:150px;margin-bottom:8px;opacity:0;visibility:hidden;transition:all .3s;box-shadow:0 5px 15px rgba(0,0,0,.2);z-index:20;pointer-events:none}
.pin-tooltip-globe::after{content:'';position:absolute;top:100%;left:50%;transform:translateX(-50%);border-width:6px;border-style:solid;border-color:#fff transparent transparent transparent}
.globe-pin:hover .pin-tooltip-globe{opacity:1;visibility:visible;transform:translateX(-50%) translateY(-5px)}
.pin-tooltip-globe h4{color:#1a202c;font-size:.8rem;margin-bottom:.2rem}
.pin-tooltip-globe p{color:#718096;font-size:.65rem;margin-bottom:.3rem}
.pin-tooltip-globe .pin-stats{display:flex;gap:.3rem;font-size:.6rem}
.pin-tooltip-globe .pin-stats span{background:#ecfdf5;padding:.15rem .4rem;border-radius:12px;color:#16a34a}
.impact-stats-sidebar{background:rgba(255,255,255,.05);border-radius:24px;padding:1.5rem;backdrop-filter:blur(10px)}
.impact-stat-global{text-align:center;padding:1rem;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:1rem}
.impact-stat-global:last-child{border-bottom:none}
.global-icon{font-size:2rem;margin-bottom:.5rem}
.global-number{font-size:2rem;font-weight:800;color:#16a34a}
.global-label{font-size:.8rem;color:#94a3b8}
.continent-stats h4{color:#fff;font-size:1rem;margin:1.5rem 0 1rem;text-align:center}
.continent-bar{display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem}
.continent-name{font-size:.8rem;color:#cbd5e0;min-width:100px}
.bar-container{flex:1;height:6px;background:rgba(255,255,255,.1);border-radius:10px;overflow:hidden}
.bar-fill{height:100%;background:linear-gradient(90deg,#16a34a,#14532d);border-radius:10px;transition:width 1s ease}
.continent-percent{font-size:.8rem;color:#16a34a;min-width:40px;text-align:right}
.impact-highlights{display:flex;justify-content:center;gap:2rem;flex-wrap:wrap;margin-top:2rem}
.highlight-card{display:flex;align-items:center;gap:1rem;background:rgba(255,255,255,.05);padding:1rem 1.5rem;border-radius:16px;backdrop-filter:blur(10px);transition:all .3s}
.highlight-card:hover{transform:translateY(-5px);background:rgba(255,255,255,.1)}
.highlight-icon{font-size:2rem}
.highlight-card h4{color:#fff;font-size:1rem;margin-bottom:.25rem}
.highlight-card p{color:#94a3b8;font-size:.8rem}

/* Before/After Transformation Section */
.transformation-section{background:#ecfdf5;padding:5rem 2rem}
.transformation-subtitle{text-align:center;color:#718096;margin-bottom:3rem}
.beforeafter-container{display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:2rem;max-width:1200px;margin:0 auto}
.beforeafter-card{background:#fff;border-radius:24px;overflow:hidden;box-shadow:0 20px 40px rgba(0,0,0,.1);transition:transform .3s}
.beforeafter-card:hover{transform:translateY(-10px)}
.beforeafter-slider{position:relative;width:100%;overflow:hidden;cursor:ew-resize}
.beforeafter-images{position:relative;width:100%;height:300px;overflow:hidden}
.before-image,.after-image{position:absolute;top:0;left:0;width:100%;height:100%;overflow:hidden}
.before-image{width:100%;z-index:1}
.after-image{width:50%;z-index:2;border-right:3px solid #fff}
.before-placeholder,.after-placeholder{width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:1.5rem}
.before-placeholder{background:linear-gradient(135deg,#4a5568,#2d3748);color:#cbd5e0}
.after-placeholder{background:linear-gradient(135deg,#16a34a,#14532d);color:#fff}
.second-before{background:linear-gradient(135deg,#7f8c8d,#2c3e50)}
.second-after{background:linear-gradient(135deg,#059669,#065f46)}
.placeholder-icon{font-size:3rem;margin-bottom:1rem}
.placeholder-title{font-size:1.1rem;font-weight:600;margin-bottom:1rem}
.placeholder-list{display:flex;flex-direction:column;gap:.5rem}
.placeholder-list span{font-size:.8rem;display:block}
.before-label,.after-label{position:absolute;bottom:15px;padding:.25rem .75rem;border-radius:20px;font-size:.7rem;font-weight:600;z-index:3}
.before-label{left:15px;background:rgba(0,0,0,.7);color:#fff}
.after-label{right:15px;background:rgba(22,163,74,.9);color:#fff}
.slider-handle{position:absolute;top:0;left:50%;width:4px;height:100%;background:#fff;z-index:10;transform:translateX(-50%);cursor:ew-resize}
.slider-circle{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:40px;height:40px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;gap:.25rem;box-shadow:0 2px 10px rgba(0,0,0,.2);cursor:ew-resize}
.slider-circle span{font-size:.8rem;color:#16a34a}
.beforeafter-stats{display:flex;justify-content:space-around;padding:1.5rem;background:#f8fafc;border-top:1px solid #e2e8f0}
.stat-item{text-align:center}
.stat-change{display:block;font-size:1.3rem;font-weight:800}
.stat-change.positive{color:#16a34a}
.stat-change.positive::before{content:'↓ '}
.stat-label{font-size:.7rem;color:#718096}
.testimonial-small{padding:1rem 1.5rem 0;font-size:.9rem;color:#4a5568;font-style:italic;text-align:center}
.testimonial-author-small{padding:.5rem 1.5rem 1.5rem;font-size:.8rem;color:#16a34a;font-weight:600;text-align:center}

/* Timeline Section */
.timeline-section{background:linear-gradient(135deg,#f8fafc,#e2e8f0);padding:5rem 2rem}
.timeline-subtitle{text-align:center;color:#718096;margin-bottom:4rem}
.timeline{position:relative;max-width:1200px;margin:0 auto;padding:2rem 0}
.timeline::after{content:'';position:absolute;width:4px;background:linear-gradient(180deg,#16a34a,#14532d,#16a34a);top:0;bottom:0;left:50%;margin-left:-2px;border-radius:10px;animation:growLine 1.5s ease-out}
@keyframes growLine{from{transform:scaleY(0);opacity:0}to{transform:scaleY(1);opacity:1}}
.timeline-item{padding:10px 40px;position:relative;width:50%;opacity:0;animation:fadeInUp .8s ease forwards}
.timeline-item:nth-child(1){animation-delay:.2s}
.timeline-item:nth-child(2){animation-delay:.4s}
.timeline-item:nth-child(3){animation-delay:.6s}
.timeline-item:nth-child(4){animation-delay:.8s}
.timeline-item:nth-child(5){animation-delay:1s}
.timeline-item:nth-child(6){animation-delay:1.2s}
@keyframes fadeInUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
.timeline-item.left{left:0}
.timeline-item.right{left:50%}
.timeline-badge{position:absolute;width:50px;height:50px;right:-25px;top:20px;background:linear-gradient(135deg,#16a34a,#14532d);border-radius:50%;display:flex;align-items:center;justify-content:center;z-index:1;box-shadow:0 0 0 4px #fff,0 0 0 8px rgba(22,163,74,.2);animation:pulseBadge 2s infinite}
@keyframes pulseBadge{0%,100%{box-shadow:0 0 0 4px #fff,0 0 0 8px rgba(22,163,74,.2)}50%{box-shadow:0 0 0 4px #fff,0 0 0 12px rgba(22,163,74,.4)}}
.right .timeline-badge{left:-25px}
.badge-icon{font-size:1.5rem;color:#fff}
.timeline-content{padding:1.5rem;background:#fff;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,.1);transition:all .3s;border-bottom:3px solid transparent}
.timeline-content:hover{transform:translateY(-5px);border-bottom-color:#16a34a;box-shadow:0 20px 40px rgba(22,163,74,.15)}
.timeline-date{display:inline-block;padding:.25rem 1rem;background:linear-gradient(135deg,#dcfce7,#ecfdf5);color:#16a34a;border-radius:50px;font-size:.8rem;font-weight:600;margin-bottom:1rem}
.timeline-content h3{font-size:1.3rem;color:#1a202c;margin-bottom:.75rem}
.timeline-content p{color:#4a5568;line-height:1.6;margin-bottom:1rem}
.timeline-stats{display:flex;gap:1rem;flex-wrap:wrap;margin-top:.5rem}
.timeline-stats span{display:inline-flex;align-items:center;gap:.25rem;font-size:.8rem;color:#718096;background:#f8fafc;padding:.25rem .75rem;border-radius:50px}
.timeline-milestones{display:flex;justify-content:center;gap:3rem;margin-top:4rem;flex-wrap:wrap}
.milestone{text-align:center;padding:1.5rem 2rem;background:#fff;border-radius:20px;min-width:180px;transition:all .3s;box-shadow:0 10px 20px rgba(0,0,0,.05)}
.milestone:hover{transform:translateY(-10px);background:linear-gradient(135deg,#16a34a,#14532d);color:#fff}
.milestone-number{display:block;font-size:2.5rem;font-weight:800;color:#16a34a;margin-bottom:.5rem;transition:color .3s}
.milestone:hover .milestone-number{color:#fff}
.milestone-label{font-size:.9rem;color:#4a5568;font-weight:500;transition:color .3s}
.milestone:hover .milestone-label{color:#fff}

/* Learn More Modal */
.learnmore-modal{display:none;position:fixed;z-index:2000;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.8);animation:fadeIn .3s ease}
.learnmore-modal-content{background:linear-gradient(135deg,#fff,#f0fdf4);margin:3% auto;width:90%;max-width:650px;border-radius:32px;animation:slideUp .5s ease;overflow:hidden;box-shadow:0 25px 50px -12px rgba(0,0,0,.5)}
.learnmore-modal-header{background:linear-gradient(135deg,#16a34a,#14532d);padding:2rem;text-align:center;position:relative;color:#fff}
.learnmore-modal-close{position:absolute;top:1rem;right:1.5rem;font-size:2rem;cursor:pointer;opacity:.8}
.learnmore-modal-close:hover{opacity:1;transform:scale(1.1)}
.learnmore-logo{display:flex;align-items:center;justify-content:center;gap:.5rem;margin-bottom:.5rem}
.learnmore-logo-icon{font-size:2.5rem}
.learnmore-modal-header h2{margin:0;font-size:1.8rem}
.learnmore-tagline{font-size:.9rem;opacity:.9;font-style:italic;margin-top:.5rem}
.learnmore-modal-body{padding:2rem}
.learnmore-vision{text-align:center;margin-bottom:2rem}
.learnmore-vision h3{color:#16a34a;font-size:1.3rem;margin-bottom:.75rem}
.learnmore-vision p{color:#4a5568;line-height:1.6}
.learnmore-stats{display:flex;justify-content:space-around;gap:1rem;margin-bottom:2rem;flex-wrap:wrap}
.learnmore-stat-card{text-align:center;padding:1rem;background:#fff;border-radius:20px;flex:1;min-width:100px;box-shadow:0 4px 10px rgba(0,0,0,.05)}
.learnmore-stat-card:hover{transform:translateY(-5px)}
.stat-emoji{font-size:2rem;display:block;margin-bottom:.5rem}
.learnmore-values h3{color:#16a34a;font-size:1.2rem;margin-bottom:1rem;text-align:center}
.values-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-bottom:2rem}
.value-item{display:flex;align-items:center;gap:.75rem;padding:.75rem;background:#fff;border-radius:16px;transition:all .3s}
.value-item:hover{background:#ecfdf5;transform:translateX(5px)}
.value-item span:first-child{font-size:1.8rem}
.value-item strong{display:block;color:#1a202c;font-size:.9rem}
.value-item p{font-size:.75rem;color:#718096;margin:0}
.learnmore-quote{background:linear-gradient(135deg,#f0fdf4,#dcfce7);padding:1.5rem;border-radius:20px;text-align:center;margin-bottom:2rem;position:relative}
.quote-icon{font-size:2rem;position:absolute;top:-10px;left:10px;opacity:.3}
.learnmore-quote p{font-size:1rem;color:#2d3748;font-style:italic;margin-bottom:.5rem}
.quote-author{font-size:.8rem;color:#16a34a;font-weight:600}
.learnmore-cta{text-align:center}
.learnmore-cta p{font-size:1rem;font-weight:600;color:#1a202c;margin-bottom:1rem}
.learnmore-buttons{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap}
.btn-learnmore-primary{padding:.8rem 1.8rem;background:linear-gradient(135deg,#16a34a,#14532d);color:#fff;text-decoration:none;border-radius:50px;font-weight:600}
.btn-learnmore-primary:hover{transform:translateY(-3px);box-shadow:0 10px 25px rgba(22,163,74,.3)}
.btn-learnmore-secondary{padding:.8rem 1.8rem;background:transparent;color:#16a34a;text-decoration:none;border-radius:50px;font-weight:600;border:2px solid #16a34a}
.btn-learnmore-secondary:hover{background:#16a34a;color:#fff;transform:translateY(-3px)}

/* Responsive */
@media (max-width:768px){
.hero-container{grid-template-columns:1fr;gap:2rem;text-align:center}
.hero-title{font-size:2.5rem}
.hero-buttons{justify-content:center}
.image-wrapper{height:280px}
.floating-card{padding:.5rem 1rem;font-size:.8rem}
.journey-timeline{flex-direction:column}
.journey-arrow{transform:rotate(90deg)}
.stats-animated-grid{gap:1rem}
.animated-stat{min-width:150px;padding:1.5rem}
.animated-number{font-size:2rem}
.impact-container{grid-template-columns:1fr}
.globe{max-width:280px;margin:0 auto}
.beforeafter-container{grid-template-columns:1fr}
.beforeafter-images{height:250px}
.timeline::after{left:31px}
.timeline-item{width:100%;padding-left:70px;padding-right:25px}
.timeline-item.right{left:0}
.timeline-badge{left:6px!important;right:auto!important;width:40px;height:40px}
.badge-icon{font-size:1.2rem}
.timeline-milestones{gap:1rem}
.milestone{min-width:130px;padding:1rem}
.milestone-number{font-size:1.8rem}
.values-grid{grid-template-columns:1fr}
.pin-tooltip-globe{min-width:120px}
}
</style>

<script>
let currentQuestion=1;const totalQuestions=5;let userAnswers={goal:null,diet:null,activity:null,challenge:null,restriction:null};
document.addEventListener('DOMContentLoaded',function(){initQuizOptions();updateNavigationButtons();animateBars();initBeforeAfterSliders();});
function initQuizOptions(){document.querySelectorAll('.quiz-option').forEach(opt=>{opt.addEventListener('click',function(){const parent=this.closest('.quiz-question');const qid=parent.dataset.question;parent.querySelectorAll('.quiz-option').forEach(s=>s.classList.remove('selected'));this.classList.add('selected');saveAnswer(qid,this.dataset.value);});});}
function saveAnswer(qid,val){const q=parseInt(qid);if(q===1)userAnswers.goal=val;else if(q===2)userAnswers.diet=val;else if(q===3)userAnswers.activity=val;else if(q===4)userAnswers.challenge=val;else if(q===5)userAnswers.restriction=val;updateNavigationButtons();}
function updateNavigationButtons(){const q=document.querySelector(`.quiz-question[data-question="${currentQuestion}"]`);const has=q?!!q.querySelector('.quiz-option.selected'):false;const next=document.querySelector('.btn-next');if(next)next.disabled=!has;}
function nextQuestion(){const q=document.querySelector(`.quiz-question[data-question="${currentQuestion}"]`);if(!q.querySelector('.quiz-option.selected')){alert('Please select an option before continuing!');return;}
if(currentQuestion<totalQuestions){document.querySelector(`.quiz-question[data-question="${currentQuestion}"]`).classList.remove('active');currentQuestion++;document.querySelector(`.quiz-question[data-question="${currentQuestion}"]`).classList.add('active');const p=(currentQuestion/totalQuestions)*100;document.querySelector('.progress-fill').style.width=p+'%';document.querySelector('.question-progress').innerText=Math.floor(p)+'%';document.querySelector('.btn-prev').style.visibility='visible';updateNavigationButtons();}else{showResults();}}
function prevQuestion(){if(currentQuestion>1){document.querySelector(`.quiz-question[data-question="${currentQuestion}"]`).classList.remove('active');currentQuestion--;document.querySelector(`.quiz-question[data-question="${currentQuestion}"]`).classList.add('active');const p=(currentQuestion/totalQuestions)*100;document.querySelector('.progress-fill').style.width=p+'%';document.querySelector('.question-progress').innerText=Math.floor(p)+'%';if(currentQuestion===1)document.querySelector('.btn-prev').style.visibility='hidden';updateNavigationButtons();}}
function showResults(){document.querySelectorAll('.quiz-question').forEach(q=>q.classList.remove('active'));document.querySelector('.quiz-navigation').style.display='none';document.getElementById('quizResult').classList.add('active');const profile=determineProfile();document.getElementById('resultIcon').innerHTML=profile.icon;document.getElementById('resultTitle').innerHTML=profile.title;document.getElementById('resultDescription').innerHTML=profile.description;let html='<h4>🎯 Personalized Recommendations:</h4><ul>';profile.recommendations.forEach(rec=>html+=`<li>${rec}</li>`);html+='</ul>';document.getElementById('resultRecommendations').innerHTML=html;document.getElementById('resultIcon').classList.add('winner');setTimeout(()=>document.getElementById('resultIcon').classList.remove('winner'),600);}
function determineProfile(){const{goal,diet,activity}=userAnswers;if(goal==='weight-loss')return{icon:'🏋️‍♀️',title:'The Weight Loss Warrior',description:'Focused on losing weight while maintaining good nutrition.',recommendations:['🥗 High-protein, low-calorie meal plans','📉 Track your calorie deficit daily','🍽️ 4-5 small meals per day','💪 Combine with light exercise']};if(goal==='muscle-gain')return{icon:'💪',title:'The Muscle Builder',description:'Building muscle mass and improving strength.',recommendations:['🍗 High-protein meals','🥑 Healthy fats','🍚 Complex carbs for energy','⏰ Protein timing around workouts']};if(diet==='vegan')return{icon:'🌱',title:'The Plant-Based Pioneer',description:'Vegan lifestyle with all essential nutrients.',recommendations:['🌿 Complete protein combinations','🥬 Iron-rich leafy greens','💊 B12 supplementation','🥑 Omega-3 from flaxseeds']};if(diet==='keto')return{icon:'🥑',title:'The Keto Champion',description:'Low carb, high fat lifestyle.',recommendations:['🥩 Healthy fats from avocados','🥬 Low-carb vegetables','💧 Electrolyte balance','📊 Track your macros']};if(activity==='very-active')return{icon:'🏃‍♂️',title:'The High-Performance Athlete',description:'Active lifestyle requiring optimal fuel.',recommendations:['⚡ Complex carbs for sustained energy','💪 Protein for muscle repair','💧 Enhanced hydration','🍌 Pre-workout snacks']};return{icon:'🌟',title:'The Balanced Achiever',description:'Maintaining a healthy, balanced lifestyle.',recommendations:['🥗 Balanced meals','🚶 30 minutes of daily movement','💧 Drink 8+ glasses of water','😴 Prioritize 7-8 hours of sleep']};}
function restartQuiz(){userAnswers={goal:null,diet:null,activity:null,challenge:null,restriction:null};document.querySelectorAll('.quiz-option').forEach(opt=>opt.classList.remove('selected'));currentQuestion=1;document.getElementById('quizResult').classList.remove('active');document.querySelector('.quiz-navigation').style.display='flex';document.querySelector('.quiz-question[data-question="1"]').classList.add('active');document.querySelector('.btn-prev').style.visibility='hidden';document.querySelector('.progress-fill').style.width='20%';document.querySelector('.question-progress').innerText='20%';for(let i=2;i<=totalQuestions;i++){const q=document.querySelector(`.quiz-question[data-question="${i}"]`);if(q)q.classList.remove('active');}updateNavigationButtons();}
function animateBars(){const bars=document.querySelectorAll('.bar-fill');const obs=new IntersectionObserver((entries)=>{entries.forEach(e=>{if(e.isIntersecting){const bar=e.target;const w=bar.style.width;bar.style.width='0';setTimeout(()=>bar.style.width=w,100);obs.unobserve(bar);}});});bars.forEach(bar=>obs.observe(bar));}
function initBeforeAfterSliders(){document.querySelectorAll('.beforeafter-slider').forEach(slider=>{const container=slider.querySelector('.beforeafter-images');const after=slider.querySelector('.after-image');const handle=slider.querySelector('.slider-handle');if(!container||!after||!handle)return;let dragging=false;function update(p){after.style.width=p+'%';handle.style.left=p+'%';}handle.addEventListener('mousedown',()=>dragging=true);window.addEventListener('mouseup',()=>dragging=false);container.addEventListener('mousemove',(e)=>{if(!dragging)return;const rect=container.getBoundingClientRect();let p=((e.clientX-rect.left)/rect.width)*100;p=Math.min(Math.max(p,0),100);update(p);});container.addEventListener('touchmove',(e)=>{const rect=container.getBoundingClientRect();let p=((e.touches[0].clientX-rect.left)/rect.width)*100;p=Math.min(Math.max(p,0),100);update(p);});});}
function openFeatureModal(type){const id=type==='ai'?'modal-ai':(type==='sustainable'?'modal-sustainable':'modal-tracking');document.getElementById(id).style.display='block';}
function closeFeatureModal(type){const id=type==='ai'?'modal-ai':(type==='sustainable'?'modal-sustainable':'modal-tracking');document.getElementById(id).style.display='none';}
function openLearnMoreModal(){document.getElementById('modal-learnmore').style.display='block';}
function closeLearnMoreModal(){document.getElementById('modal-learnmore').style.display='none';}
window.onclick=function(e){document.querySelectorAll('.feature-modal').forEach(m=>{if(e.target==m)m.style.display='none';});const lm=document.getElementById('modal-learnmore');if(e.target==lm)lm.style.display='none';}
const counters=document.querySelectorAll('.animated-number');let animated=false;
function animateNumbers(){if(animated)return;counters.forEach(c=>{const target=parseInt(c.dataset.target);let current=0;const inc=target/80;const update=()=>{if(current<target){current+=inc;c.innerText=Math.ceil(current);setTimeout(update,20);}else c.innerText=target;};update();});animated=true;}
const statsSec=document.querySelector('.stats-showcase');if(statsSec){const obs=new IntersectionObserver((entries)=>{entries.forEach(e=>{if(e.isIntersecting){animateNumbers();obs.unobserve(e.target);}});});obs.observe(statsSec);}
let currentIdx=0;const testimonials=document.querySelectorAll('.testimonial-card');const dots=document.querySelectorAll('.dot');
function showTestimonial(idx){testimonials.forEach((t,i)=>{t.classList.remove('active');if(dots[i])dots[i].classList.remove('active');});if(testimonials[idx])testimonials[idx].classList.add('active');if(dots[idx])dots[idx].classList.add('active');currentIdx=idx;}
function nextTestimonial(){currentIdx=(currentIdx+1)%testimonials.length;showTestimonial(currentIdx);}
function currentTestimonial(idx){showTestimonial(idx);}
let autoRotate=setInterval(nextTestimonial,5000);const carousel=document.querySelector('.testimonials-carousel');if(carousel){carousel.addEventListener('mouseenter',()=>clearInterval(autoRotate));carousel.addEventListener('mouseleave',()=>{autoRotate=setInterval(nextTestimonial,5000);});}
</script>
