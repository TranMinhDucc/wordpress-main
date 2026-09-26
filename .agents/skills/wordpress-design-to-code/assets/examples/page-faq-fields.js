/* Reference implementation. Requires the matching PHP metabox markup. */
(() => {
  'use strict';
  function initialize(root) {
    if (root.dataset.faqInitialized === 'true') return;
    const initial = root.querySelector('[data-faq-initial]');
    const payload = root.querySelector('[data-faq-payload]');
    const rows = root.querySelector('[data-faq-rows]');
    const add = root.querySelector('[data-faq-add]');
    const status = root.querySelector('[data-faq-status]');
    const form = root.closest('form');
    if (!initial || !payload || !rows || !add || !status || !form) return;
    payload.disabled = true;
    const limits = {
      items: Number(root.dataset.maxItems),
      question: Number(root.dataset.maxQuestion),
      answer: Number(root.dataset.maxAnswer),
      bytes: Number(root.dataset.maxBytes),
    };
    const error = () => {
      payload.disabled = true;
      status.textContent = root.dataset.messageError;
      return false;
    };
    try {
      if (Object.values(limits).some(value => !Number.isInteger(value) || value < 1)) {
        error(); return;
      }
      const items = JSON.parse(initial.value);
      if (!Array.isArray(items) || items.length > limits.items || items.some(item =>
        !item || Array.isArray(item) || typeof item.question !== 'string'
        || typeof item.answer !== 'string' || Object.keys(item).some(key => !['question', 'answer'].includes(key))
      )) { error(); return; }
      let nextId = 0;
      const instanceId = `kwdtc-faq-${Array.from(document.querySelectorAll('[data-kwdtc-faq]')).indexOf(root)}`;
      const collect = () => Array.from(rows.children).map(row => ({
        question: row.querySelector('[data-question]').value,
        answer: row.querySelector('[data-answer]').value,
      }));
      function sync() {
        try {
          const data = collect();
          if (data.length > limits.items || data.some(item =>
            [...item.question].length > limits.question || [...item.answer].length > limits.answer
            || Boolean(item.question.trim()) !== Boolean(item.answer.trim())
          )) return error();
          const json = JSON.stringify(data);
          if (new TextEncoder().encode(json).length > limits.bytes) return error();
          payload.value = json;
          payload.disabled = false;
          status.textContent = root.dataset.messageReady;
          return true;
        } catch (_) { return error(); }
      }
      function refreshButtons() {
        Array.from(rows.children).forEach((row, index) => {
          row.querySelector('[data-move-up]').disabled = index === 0;
          row.querySelector('[data-move-down]').disabled = index === rows.children.length - 1;
          row.querySelector('legend').textContent = `FAQ ${index + 1}`;
        });
        add.disabled = rows.children.length >= limits.items;
      }
      function makeButton(text, attr, callback) {
        const button = document.createElement('button');
        button.type = 'button'; button.className = 'button';
        button.textContent = text; button.setAttribute(attr, '');
        button.addEventListener('click', callback);
        return button;
      }
      function makeRow(item) {
        const row = document.createElement('fieldset');
        const legend = document.createElement('legend');
        row.append(legend);
        const id = `${instanceId}-${nextId++}`;
        [['question', root.dataset.labelQuestion], ['answer', root.dataset.labelAnswer]].forEach(([key, text]) => {
          const paragraph = document.createElement('p');
          const label = document.createElement('label');
          const input = document.createElement(key === 'question' ? 'input' : 'textarea');
          if (key === 'question') input.type = 'text';
          else input.rows = 4;
          input.id = `${id}-${key}`; label.htmlFor = input.id;
          label.textContent = text; input.className = 'widefat';
          input.setAttribute(`data-${key}`, ''); input.value = item[key];
          // Validate without maxlength: never silently truncate loaded content.
          input.addEventListener('input', sync);
          paragraph.append(label, document.createElement('br'), input); row.append(paragraph);
        });
        const actions = document.createElement('p');
        const up = makeButton(root.dataset.labelUp, 'data-move-up', () => {
          const previous = row.previousElementSibling;
          if (previous) rows.insertBefore(row, previous);
          refreshButtons(); sync(); row.querySelector('[data-question]').focus();
        });
        const down = makeButton(root.dataset.labelDown, 'data-move-down', () => {
          const next = row.nextElementSibling;
          if (next) rows.insertBefore(next, row);
          refreshButtons(); sync(); row.querySelector('[data-question]').focus();
        });
        const remove = makeButton(root.dataset.labelDelete, 'data-delete-row', () => {
          const focusRow = row.nextElementSibling || row.previousElementSibling;
          row.remove(); refreshButtons(); sync();
          (focusRow ? focusRow.querySelector('[data-question]') : add).focus();
        });
        actions.append(up, document.createTextNode(' '), down, document.createTextNode(' '), remove);
        row.append(actions); return row;
      }
      items.forEach(item => rows.append(makeRow(item)));
      add.addEventListener('click', () => {
        if (rows.children.length >= limits.items) return;
        const row = makeRow({question: '', answer: ''});
        rows.append(row); refreshButtons(); sync(); row.querySelector('[data-question]').focus();
      });
      form.addEventListener('submit', event => {
        if (!sync()) {
          event.preventDefault(); status.setAttribute('tabindex', '-1'); status.focus();
        }
      });
      root.dataset.faqInitialized = 'true'; refreshButtons(); sync();
    } catch (_) { error(); }
  }
  const boot = () => document.querySelectorAll('[data-kwdtc-faq]').forEach(initialize);
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot, {once: true});
  else boot();
})();
