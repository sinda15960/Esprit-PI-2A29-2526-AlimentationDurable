<!-- Period Comparison Tool -->
<div class="comparison-container">
    <div class="comparison-header">
        <h2>📊 Period Comparison Tool</h2>
        <p>Compare KPIs between two different time periods</p>
    </div>
    
    <div class="comparison-controls">
        <div class="period-selector">
            <h3>Period 1</h3>
            <select id="period1Type" class="period-type">
                <option value="this_month">This Month</option>
                <option value="last_month">Last Month</option>
                <option value="this_quarter">This Quarter</option>
                <option value="last_quarter">Last Quarter</option>
                <option value="this_year">This Year</option>
                <option value="last_year">Last Year</option>
                <option value="custom">Custom Range</option>
            </select>
            <div id="customRange1" style="display: none;" class="custom-range">
                <input type="date" id="date1_start"> to <input type="date" id="date1_end">
            </div>
        </div>
        
        <div class="vs-divider">VS</div>
        
        <div class="period-selector">
            <h3>Period 2</h3>
            <select id="period2Type" class="period-type">
                <option value="this_month">This Month</option>
                <option value="last_month" selected>Last Month</option>
                <option value="this_quarter">This Quarter</option>
                <option value="last_quarter">Last Quarter</option>
                <option value="this_year">This Year</option>
                <option value="last_year">Last Year</option>
                <option value="custom">Custom Range</option>
            </select>
            <div id="customRange2" style="display: none;" class="custom-range">
                <input type="date" id="date2_start"> to <input type="date" id="date2_end">
            </div>
        </div>
        
        <button class="compare-btn" onclick="comparePeriods()">🔄 Compare Now</button>
    </div>
    
    <div class="comparison-results" id="comparisonResults">
        <div class="loading">Loading comparison data...</div>
    </div>
    
    <div class="export-comparison">
        <button class="export-compare-btn" onclick="exportComparison()">📥 Export Comparison (PDF/CSV)</button>
    </div>
</div>

<style>
.comparison-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.comparison-header {
    text-align: center;
    margin-bottom: 2rem;
}

.comparison-header h2 {
    font-size: 1.8rem;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.comparison-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 20px;
}

.period-selector {
    text-align: center;
    min-width: 250px;
}

.period-selector h3 {
    font-size: 1rem;
    color: #475569;
    margin-bottom: 0.5rem;
}

.period-type {
    width: 100%;
    padding: 0.6rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.9rem;
    background: white;
    cursor: pointer;
}

.custom-range {
    margin-top: 0.5rem;
    display: flex;
    gap: 0.5rem;
    align-items: center;
    justify-content: center;
}

.custom-range input {
    padding: 0.4rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
}

.vs-divider {
    font-size: 1.5rem;
    font-weight: bold;
    color: #16a34a;
    background: #dcfce7;
    padding: 0.5rem 1rem;
    border-radius: 50px;
}

.compare-btn {
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.compare-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22,163,74,0.3);
}

.comparison-results {
    margin-top: 1rem;
}

.comparison-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.comparison-card {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s;
}

.comparison-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.comparison-card h4 {
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 0.5rem;
}

.comparison-values {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 0.5rem;
}

.value-p1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e293b;
}

.value-p2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e293b;
}

.comparison-diff {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.diff-positive {
    background: #dcfce7;
    color: #166534;
}

.diff-positive::before {
    content: '▲ ';
}

.diff-negative {
    background: #fee2e2;
    color: #991b1b;
}

.diff-negative::before {
    content: '▼ ';
}

.diff-neutral {
    background: #f1f5f9;
    color: #64748b;
}

.comparison-chart {
    margin-top: 1rem;
    height: 100px;
    background: linear-gradient(90deg, #16a34a 0%, #8b5cf6 100%);
    border-radius: 8px;
    position: relative;
    overflow: hidden;
}

.chart-bar {
    position: absolute;
    bottom: 0;
    width: 40%;
    background: rgba(255,255,255,0.8);
    transition: all 0.5s;
}

.export-compare-btn {
    margin-top: 2rem;
    width: 100%;
    padding: 0.8rem;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.export-compare-btn:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

.loading {
    text-align: center;
    padding: 2rem;
    color: #94a3b8;
}

@media (max-width: 768px) {
    .comparison-controls {
        flex-direction: column;
    }
    .vs-divider {
        transform: rotate(90deg);
    }
    .comparison-values {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<script>
let currentComparisonData = null;

function comparePeriods() {
    const period1Type = document.getElementById('period1Type').value;
    const period2Type = document.getElementById('period2Type').value;
    
    let period1 = getPeriodDates(period1Type, 1);
    let period2 = getPeriodDates(period2Type, 2);
    
    // Simuler des données (à remplacer par des données réelles de la BDD)
    const comparison = {
        period1: { label: formatPeriodLabel(period1Type, period1), data: generateMockData() },
        period2: { label: formatPeriodLabel(period2Type, period2), data: generateMockData() }
    };
    
    currentComparisonData = comparison;
    renderComparisonResults(comparison);
}

function getPeriodDates(type, periodNum) {
    const now = new Date();
    let start, end;
    
    if (type === 'custom') {
        const startInput = document.getElementById(`date${periodNum}_start`).value;
        const endInput = document.getElementById(`date${periodNum}_end`).value;
        start = new Date(startInput);
        end = new Date(endInput);
    } else {
        switch(type) {
            case 'this_month':
                start = new Date(now.getFullYear(), now.getMonth(), 1);
                end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                break;
            case 'last_month':
                start = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                end = new Date(now.getFullYear(), now.getMonth(), 0);
                break;
            case 'this_quarter':
                let quarter = Math.floor(now.getMonth() / 3);
                start = new Date(now.getFullYear(), quarter * 3, 1);
                end = new Date(now.getFullYear(), (quarter + 1) * 3, 0);
                break;
            case 'last_quarter':
                let lastQuarter = Math.floor(now.getMonth() / 3) - 1;
                start = new Date(now.getFullYear(), lastQuarter * 3, 1);
                end = new Date(now.getFullYear(), (lastQuarter + 1) * 3, 0);
                break;
            case 'this_year':
                start = new Date(now.getFullYear(), 0, 1);
                end = new Date(now.getFullYear(), 11, 31);
                break;
            case 'last_year':
                start = new Date(now.getFullYear() - 1, 0, 1);
                end = new Date(now.getFullYear() - 1, 11, 31);
                break;
        }
    }
    
    return { start, end };
}

function formatPeriodLabel(type, dates) {
    if (type === 'custom') {
        return `${dates.start.toLocaleDateString()} - ${dates.end.toLocaleDateString()}`;
    }
    return type.replace('_', ' ').toUpperCase();
}

function generateMockData() {
    return {
        newUsers: Math.floor(Math.random() * 100) + 20,
        activeUsers: Math.floor(Math.random() * 500) + 100,
        totalLogins: Math.floor(Math.random() * 2000) + 500,
        messagesSent: Math.floor(Math.random() * 50) + 5,
        recipesAdded: Math.floor(Math.random() * 30) + 5,
        avgSessionTime: Math.floor(Math.random() * 600) + 120
    };
}

function renderComparisonResults(comparison) {
    const container = document.getElementById('comparisonResults');
    const data1 = comparison.period1.data;
    const data2 = comparison.period2.data;
    
    const metrics = [
        { key: 'newUsers', label: '👥 New Users', unit: '' },
        { key: 'activeUsers', label: '🟢 Active Users', unit: '' },
        { key: 'totalLogins', label: '🔑 Total Logins', unit: '' },
        { key: 'messagesSent', label: '📬 Messages Sent', unit: '' },
        { key: 'recipesAdded', label: '🍽️ Recipes Added', unit: '' },
        { key: 'avgSessionTime', label: '⏱️ Avg Session Time', unit: 's' }
    ];
    
    let html = `
        <div class="comparison-header-compact">
            <div class="period-header">${comparison.period1.label}</div>
            <div class="period-header">${comparison.period2.label}</div>
        </div>
        <div class="comparison-grid">
    `;
    
    metrics.forEach(metric => {
        const val1 = data1[metric.key];
        const val2 = data2[metric.key];
        const diff = ((val2 - val1) / val1 * 100).toFixed(1);
        const diffClass = diff > 0 ? 'diff-positive' : (diff < 0 ? 'diff-negative' : 'diff-neutral');
        const maxVal = Math.max(val1, val2);
        
        html += `
            <div class="comparison-card">
                <h4>${metric.label}</h4>
                <div class="comparison-values">
                    <div class="value-p1">${val1.toLocaleString()}${metric.unit}</div>
                    <div class="vs-small">VS</div>
                    <div class="value-p2">${val2.toLocaleString()}${metric.unit}</div>
                </div>
                <div class="comparison-diff ${diffClass}">${Math.abs(diff)}%</div>
                <div class="comparison-chart" style="height: 80px; background: #e2e8f0; border-radius: 8px; margin-top: 1rem; position: relative;">
                    <div class="chart-bar" style="height: ${(val1/maxVal)*100}%; width: 40%; left: 10%; bottom: 0; background: #16a34a;"></div>
                    <div class="chart-bar" style="height: ${(val2/maxVal)*100}%; width: 40%; left: 50%; bottom: 0; background: #8b5cf6;"></div>
                    <div style="position: absolute; bottom: -20px; left: 15%; font-size: 10px;">P1</div>
                    <div style="position: absolute; bottom: -20px; left: 65%; font-size: 10px;">P2</div>
                </div>
            </div>
        `;
    });
    
    html += `</div>`;
    container.innerHTML = html;
}

function exportComparison() {
    if (!currentComparisonData) {
        alert('Please run a comparison first');
        return;
    }
    
    // Export to CSV
    let csv = 'Metric,Period 1,Period 2,Change (%)\n';
    const data1 = currentComparisonData.period1.data;
    const data2 = currentComparisonData.period2.data;
    
    for (const [key, val1] of Object.entries(data1)) {
        const val2 = data2[key];
        const diff = ((val2 - val1) / val1 * 100).toFixed(1);
        csv += `${key},${val1},${val2},${diff}%\n`;
    }
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `comparison_${new Date().toISOString().slice(0,19)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
    
    showToast('✅ Comparison exported to CSV');
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.textContent = msg;
    toast.style.cssText = 'position:fixed;bottom:20px;right:20px;background:#16a34a;color:white;padding:10px20px;border-radius:10px;z-index:9999';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Custom range visibility
document.querySelectorAll('.period-type').forEach(select => {
    select.addEventListener('change', function() {
        const id = this.id;
        const num = id === 'period1Type' ? 1 : 2;
        const customDiv = document.getElementById(`customRange${num}`);
        if (this.value === 'custom') {
            customDiv.style.display = 'flex';
        } else {
            customDiv.style.display = 'none';
        }
    });
});
</script>
