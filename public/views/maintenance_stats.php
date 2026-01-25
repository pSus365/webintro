<?php
$pageTitle = "Stitch Design - Statystyki Utrzymania";
$extraStyles = ['/public/styles/maintenance.css'];
include 'partials/header.php';
?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="page-header-row">
    <h1 class="page-title">Statystyki Kosztów</h1>
    <a href="/maintenance" class="btn-primary" style="background-color: var(--text-muted); text-decoration: none;">
        <span class="material-symbols-outlined" style="font-size: 1.25rem;">arrow_back</span>
        <span>Powrót do tabeli</span>
    </a>
</div>

<!-- KPI Cards -->
<div class="stats-grid"
    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card"
        style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid var(--border-color);">
        <div class="stat-content">
            <h3 style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">
                Przewidywany Koszt Całkowity
            </h3>
            <p style="font-size: 2rem; font-weight: 700; color: var(--text-dark); margin-top: 0.5rem;">
                <?= number_format($totalCost, 2, ',', ' ') ?> PLN
            </p>
        </div>
    </div>

    <div class="stat-card"
        style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid var(--border-color);">
        <div class="stat-content">
            <h3 style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">
                Średni Koszt na Pojazd
            </h3>
            <p style="font-size: 2rem; font-weight: 700; color: var(--text-dark); margin-top: 0.5rem;">
                <?= ($vehicleCount > 0) ? number_format($totalCost / $vehicleCount, 2, ',', ' ') : 0 ?>
                PLN
            </p>
        </div>
    </div>

    <div class="stat-card"
        style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid var(--border-color);">
        <div class="stat-content">
            <h3 style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">
                Nadchodzące Przeglądy
            </h3>
            <p style="font-size: 2rem; font-weight: 700; color: var(--text-dark); margin-top: 0.5rem;">
                <?= $upcomingServiceCount ?>
            </p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">

    <!-- Expense by Type Chart -->
    <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Koszty wg Typu Pojazdu</h3>
        <canvas id="typeChart"></canvas>
    </div>

    <!-- Projection Chart -->
    <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Symulacja Kosztów (6 miesięcy)</h3>
        <canvas id="projectionChart"></canvas>
        <div style="margin-top: 1rem; font-size: 0.875rem; color: var(--text-muted);">
            *Symulacja zakłada stały wzrost kosztów eksploatacji.
        </div>
    </div>
</div>

<!-- Interactive Projection -->
<div
    style="margin-top: 1.5rem; background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid var(--border-color);">
    <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Kalkulator Prognoz</h3>
    <div style="display: flex; gap: 1rem; align-items: flex-end;">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <label for="inflation" style="font-size: 0.875rem; font-weight: 500;">Wzrost cen serwisu
                (%)</label>
            <input type="number" id="inflation" value="5"
                style="padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.375rem;">
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <label for="months" style="font-size: 0.875rem; font-weight: 500;">Okres (miesiące)</label>
            <input type="number" id="months" value="12"
                style="padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.375rem;">
        </div>
        <button onclick="calculateProjection()" class="btn-primary">Oblicz</button>
    </div>
    <p id="calculationResult"
        style="margin-top: 1rem; font-size: 1.25rem; font-weight: 500; color: var(--primary-color);">
        Kliknij Oblicz, aby zobaczyć prognozę.
    </p>
</div>

<!-- Chart Scripts -->
<script>
    // Data from PHP
    const typeLabels = <?= json_encode(array_keys($typeCosts)) ?>;
    const typeData = <?= json_encode(array_values($typeCosts)) ?>;
    const totalBaseCost = <?= json_encode($totalCost ?? 0) ?>;

    // Type Chart (Pie/Doughnut)
    const ctxType = document.getElementById('typeChart').getContext('2d');
    new Chart(ctxType, {
        type: 'doughnut',
        data: {
            labels: typeLabels,
            datasets: [{
                label: 'Koszt (PLN)',
                data: typeData,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                ],
                borderWidth: 1
            }]
        }
    });

    // Projection Chart (Bar)
    const ctxProj = document.getElementById('projectionChart').getContext('2d');
    const months = ['M 1', 'M 2', 'M 3', 'M 4', 'M 5', 'M 6'];

    // Simulate cumulative cost accumulation + some monthly variance
    const projectedData = months.map((_, i) => {
        const monthlyAvg = totalBaseCost / 6; // Rough monthly average
        return Math.round(monthlyAvg * (i + 1));
    });

    new Chart(ctxProj, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Skumulowane Wydatki (PLN)',
                data: projectedData,
                backgroundColor: 'rgba(17, 115, 212, 0.5)',
                borderColor: 'rgba(17, 115, 212, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Simple JS Calculator for the form
    function calculateProjection() {
        const inflation = parseFloat(document.getElementById('inflation').value) / 100;
        const months = parseInt(document.getElementById('months').value);

        // Assume totalCost is for a typical service cycle (e.g. 1 year). 
        // Let's project linear cost + inflation.
        // Cost per month approx:
        const monthlyCost = totalBaseCost / 12;

        let futureTotalKey = 0;
        for (let i = 1; i <= months; i++) {
            futureTotalKey += monthlyCost * (1 + inflation);
        }

        document.getElementById('calculationResult').innerText =
            `Szacowany koszt po ${months} miesiącach (przy inflacji): ${futureTotalKey.toFixed(2)} PLN`;
    }
</script>

<?php include 'partials/footer.php'; ?>