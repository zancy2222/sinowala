/*
 * ARIA grid keyboard support for the calendar.
 * Arrows move between days (and between people in the team week view), Home/End go to the
 * start/end of the week, PageUp/PageDown change month, Enter/Space opens the day.
 * Moving past the visible period asks Livewire for the next one, then restores focus.
 */
const pad = (n) => String(n).padStart(2, '0');
const toKey = (d) => `${d.getUTCFullYear()}-${pad(d.getUTCMonth() + 1)}-${pad(d.getUTCDate())}`;
const parse = (s) => {
    const [y, m, d] = s.split('-').map(Number);
    return new Date(Date.UTC(y, m - 1, d));
};
const addDays = (s, n) => {
    const d = parse(s);
    d.setUTCDate(d.getUTCDate() + n);
    return toKey(d);
};
const addMonths = (s, n) => {
    const d = parse(s);
    const day = d.getUTCDate();
    d.setUTCDate(1);
    d.setUTCMonth(d.getUTCMonth() + n);
    const last = new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth() + 1, 0)).getUTCDate();
    d.setUTCDate(Math.min(day, last));
    return toKey(d);
};
const weekday = (s) => (parse(s).getUTCDay() + 6) % 7; // Monday = 0

export function calendarGrid() {
    return {
        cells() {
            return [...this.$el.querySelectorAll('[data-date]')];
        },
        find(date, row) {
            const sel = row ? `[data-date="${date}"][data-row="${row}"]` : `[data-date="${date}"]`;
            return this.$el.querySelector(sel);
        },
        focusCell(cell) {
            this.cells().forEach((c) => c.setAttribute('tabindex', '-1'));
            cell.setAttribute('tabindex', '0');
            cell.focus();
        },
        rows() {
            return [...new Set(this.cells().map((c) => c.dataset.row).filter(Boolean))];
        },
        async go(date, row) {
            const cell = this.find(date, row);
            if (cell) return this.focusCell(cell);
            await this.$wire.goTo(date);
            this.$nextTick(() => {
                const next = this.find(date, row) ?? this.find(date);
                if (next) this.focusCell(next);
            });
        },
        key(e) {
            const cell = e.target.closest('[data-date]');
            if (!cell) return;
            const date = cell.dataset.date;
            const row = cell.dataset.row || null;
            const rows = this.rows();
            const r = row ? rows.indexOf(row) : -1;
            const vertical = row ? null : 7;
            let target = null;
            let targetRow = row;

            switch (e.key) {
                case 'ArrowRight':
                    target = addDays(date, 1);
                    break;
                case 'ArrowLeft':
                    target = addDays(date, -1);
                    break;
                case 'ArrowDown':
                    if (vertical) target = addDays(date, 7);
                    else if (r < rows.length - 1) [target, targetRow] = [date, rows[r + 1]];
                    break;
                case 'ArrowUp':
                    if (vertical) target = addDays(date, -7);
                    else if (r > 0) [target, targetRow] = [date, rows[r - 1]];
                    break;
                case 'Home':
                    target = addDays(date, -weekday(date));
                    break;
                case 'End':
                    target = addDays(date, 6 - weekday(date));
                    break;
                case 'PageUp':
                    target = addMonths(date, -1);
                    break;
                case 'PageDown':
                    target = addMonths(date, 1);
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    cell.click();
                    return;
                default:
                    return;
            }
            e.preventDefault();
            if (target) this.go(target, targetRow);
        },
    };
}
