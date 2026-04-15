export function initHistoryModal() {
  const modal = document.getElementById('modalRiwayat');
  const openBtn = document.getElementById('openModalRiwayat');
  const closeBtn = document.getElementById('closeModalRiwayat');
  const closeFooterBtn = document.getElementById('closeModalRiwayatFooter');

  if (!modal || !openBtn || !closeBtn || !closeFooterBtn) return;

  const collapseCard = (card) => {
    const icon = card.querySelector('.expand-icon');
    card.classList.remove('expanded', 'active');
    card.style.maxHeight = '6rem';
    if (icon) icon.style.transform = 'rotate(0deg)';
  };

  const expandCard = (card) => {
    const icon = card.querySelector('.expand-icon');
    card.classList.add('expanded', 'active');
    card.style.maxHeight = `${card.scrollHeight}px`;
    if (icon) icon.style.transform = 'rotate(180deg)';

    card.querySelectorAll('.lazy-load').forEach((img) => {
      if (img.dataset.src) {
        img.src = img.dataset.src;
        img.removeAttribute('data-src');
      }
    });
  };

  const closeModal = () => {
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.querySelectorAll('.card-expandable').forEach(collapseCard);
  };

  openBtn.addEventListener('click', () => {
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  });

  closeBtn.addEventListener('click', closeModal);
  closeFooterBtn.addEventListener('click', closeModal);

  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      closeModal();
    }
  });

  document.querySelectorAll('.card-expandable').forEach((card) => {
    card.addEventListener('click', function onCardClick(e) {
      if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;

      const isExpanded = this.classList.contains('expanded');
      document.querySelectorAll('.card-expandable').forEach((otherCard) => {
        if (otherCard !== this) collapseCard(otherCard);
      });

      if (isExpanded) {
        collapseCard(this);
      } else {
        expandCard(this);
      }
    });
  });
}
