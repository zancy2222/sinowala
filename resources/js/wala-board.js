/*
 * The hero's live thing: a sample team month, built as 3D blocks. Each team member's Wala day
 * stands up in orange; pressing a day adds you to it and the count for that day updates.
 * The board leans a little toward the pointer. The server renders the same board for no-JS.
 */
export function walaBoard({ total, absent, today, labels }) {
    let frame = 0;
    return {
        mine: {},
        day: today,
        ready: false,
        init() {
            requestAnimationFrame(() => (this.ready = true));
            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const fine = window.matchMedia('(pointer: fine)').matches;
            if (reduce || !fine) return;
            const stage = this.$el;
            const host = stage.closest('section') ?? stage;
            host.addEventListener('pointermove', (e) => {
                cancelAnimationFrame(frame);
                frame = requestAnimationFrame(() => {
                    const r = host.getBoundingClientRect();
                    const x = (e.clientX - r.left) / r.width - 0.5;
                    const y = (e.clientY - r.top) / r.height - 0.5;
                    stage.style.setProperty('--lean-z', `${(x * 7).toFixed(2)}deg`);
                    stage.style.setProperty('--lean-x', `${(y * -5).toFixed(2)}deg`);
                });
            });
            host.addEventListener('pointerleave', () => {
                stage.style.setProperty('--lean-z', '0deg');
                stage.style.setProperty('--lean-x', '0deg');
            });
        },
        toggle(d) {
            this.mine[d] = !this.mine[d];
            this.day = d;
        },
        isMine(d) {
            return !!this.mine[d];
        },
        present(d) {
            return total - (absent[d] ?? 0) - (this.mine[d] ? 1 : 0);
        },
        count() {
            return Object.values(this.mine).filter(Boolean).length;
        },
        status() {
            return labels.present
                .replace(':day', labels.days[this.day])
                .replace(':date', this.day)
                .replace(':present', this.present(this.day))
                .replace(':total', total);
        },
        mineLabel() {
            const n = this.count();
            return (n === 0 ? labels.mine[0] : n === 1 ? labels.mine[1] : labels.mine[2]).replace(':count', n);
        },
    };
}
