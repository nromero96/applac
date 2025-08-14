<button type="button" class="agenda__button" x-data @click="window.dispatchEvent(new CustomEvent('show-agenda'))">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.66797 13C2.66797 12.558 2.84356 12.1341 3.15612 11.8215C3.46868 11.509 3.89261 11.3334 4.33464 11.3334H13.3346" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.33464 1.33337H13.3346V14.6667H4.33464C3.89261 14.6667 3.46868 14.4911 3.15612 14.1786C2.84356 13.866 2.66797 13.4421 2.66797 13V3.00004C2.66797 2.55801 2.84356 2.13409 3.15612 1.82153C3.46868 1.50897 3.89261 1.33337 4.33464 1.33337Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    <span>My Agenda</span>
    <small>{{ $quotations_total }}</small>
</button>
