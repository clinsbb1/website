import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Placeholder from '@tiptap/extension-placeholder';

const ALLOWED_LINK_PROTOCOLS = ['http:', 'https:', 'mailto:'];

function isSafeUrl(value) {
    try {
        const url = new URL(value, window.location.origin);
        return ALLOWED_LINK_PROTOCOLS.includes(url.protocol);
    } catch {
        return false;
    }
}

function initEditor(root) {
    const editorEl = root.querySelector('[data-tiptap-editor]');
    const toolbarEl = root.querySelector('[data-tiptap-toolbar]');
    const hiddenInput = root.querySelector('[data-tiptap-content-input]');
    const imageInput = root.querySelector('[data-tiptap-image-input]');
    const form = root.closest('form');
    const uploadUrl = root.dataset.imageUploadUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    let initialContent = { type: 'doc', content: [{ type: 'paragraph' }] };
    const seedEl = root.querySelector('[data-tiptap-initial-content]');
    if (seedEl && seedEl.textContent.trim()) {
        try {
            const parsed = JSON.parse(seedEl.textContent);
            if (parsed && parsed.type === 'doc') {
                initialContent = parsed;
            }
        } catch {
            // fall back to the empty document
        }
    }

    let dirty = false;

    const editor = new Editor({
        element: editorEl,
        extensions: [
            StarterKit.configure({
                heading: { levels: [2, 3] },
                strike: false,
            }),
            Link.configure({
                openOnClick: false,
                autolink: false,
                HTMLAttributes: { target: '_blank', rel: 'noopener noreferrer' },
            }),
            Image,
            Placeholder.configure({ placeholder: 'Start writing…' }),
        ],
        content: initialContent,
        onUpdate: () => {
            dirty = true;
            syncHiddenInput();
            updateToolbarState();
        },
        onSelectionUpdate: () => updateToolbarState(),
        onTransaction: () => updateToolbarState(),
    });

    function syncHiddenInput() {
        if (hiddenInput) {
            hiddenInput.value = JSON.stringify(editor.getJSON());
        }
    }

    function updateToolbarState() {
        if (!toolbarEl) return;
        toolbarEl.querySelectorAll('[data-command]').forEach((btn) => {
            const cmd = btn.dataset.command;
            let active = false;
            if (cmd === 'h2') active = editor.isActive('heading', { level: 2 });
            else if (cmd === 'h3') active = editor.isActive('heading', { level: 3 });
            else if (cmd === 'paragraph') active = editor.isActive('paragraph');
            else active = editor.isActive(cmd);
            btn.dataset.active = active ? 'true' : 'false';
        });
    }

    if (toolbarEl) {
        toolbarEl.addEventListener('click', (event) => {
            const btn = event.target.closest('[data-command]');
            if (!btn) return;
            event.preventDefault();
            const chain = editor.chain().focus();

            switch (btn.dataset.command) {
                case 'paragraph':
                    chain.setParagraph().run();
                    break;
                case 'h2':
                    chain.toggleHeading({ level: 2 }).run();
                    break;
                case 'h3':
                    chain.toggleHeading({ level: 3 }).run();
                    break;
                case 'bold':
                    chain.toggleBold().run();
                    break;
                case 'italic':
                    chain.toggleItalic().run();
                    break;
                case 'blockquote':
                    chain.toggleBlockquote().run();
                    break;
                case 'bulletList':
                    chain.toggleBulletList().run();
                    break;
                case 'orderedList':
                    chain.toggleOrderedList().run();
                    break;
                case 'code':
                    chain.toggleCode().run();
                    break;
                case 'codeBlock':
                    chain.toggleCodeBlock().run();
                    break;
                case 'horizontalRule':
                    chain.setHorizontalRule().run();
                    break;
                case 'undo':
                    chain.undo().run();
                    break;
                case 'redo':
                    chain.redo().run();
                    break;
                case 'link': {
                    const previousUrl = editor.getAttributes('link').href || '';
                    const url = window.prompt('Link URL', previousUrl);
                    if (url === null) break;
                    if (url === '') {
                        chain.unsetLink().run();
                        break;
                    }
                    if (!isSafeUrl(url)) {
                        window.alert('That URL is not allowed. Use an http(s) or mailto link.');
                        break;
                    }
                    chain.extendMarkRange('link').setLink({ href: url }).run();
                    break;
                }
                case 'image':
                    imageInput?.click();
                    break;
            }

            syncHiddenInput();
        });
    }

    if (imageInput && uploadUrl) {
        imageInput.addEventListener('change', async () => {
            const file = imageInput.files?.[0];
            imageInput.value = '';
            if (!file) return;

            const body = new FormData();
            body.append('image', file);

            try {
                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
                    body,
                });
                if (!response.ok) {
                    const payload = await response.json().catch(() => null);
                    throw new Error(payload?.message || 'Upload failed');
                }
                const { url } = await response.json();
                const alt = window.prompt('Alt text (for accessibility)', '') || '';
                editor.chain().focus().setImage({ src: url, alt }).run();
                syncHiddenInput();
            } catch (error) {
                window.alert(error.message || 'Image upload failed.');
            }
        });
    }

    if (form) {
        form.addEventListener('submit', () => {
            syncHiddenInput();
            dirty = false;
        });
    }

    window.addEventListener('beforeunload', (event) => {
        if (!dirty) return;
        event.preventDefault();
        event.returnValue = '';
    });

    syncHiddenInput();
    updateToolbarState();
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-tiptap-root]').forEach(initEditor);
});
