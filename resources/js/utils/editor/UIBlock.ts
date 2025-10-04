import { Node, mergeAttributes } from '@tiptap/core'

declare module '@tiptap/core' {
  interface Commands<ReturnType> {
    uiblock: {
      /**
       * Chèn UIBlock vào editor với tên và optional settings
       */
      insertUIBlock: (name: string, settings?: Record<string, any>) => ReturnType
    }
  }
}

export const UIBlock = Node.create({
  name: 'uiblock',

  group: 'block',
  atom: true, // Node độc lập, không có con

  addAttributes() {
    return {
      name: {
        default: null,
        parseHTML: el => el.getAttribute('data-name'),
        renderHTML: attrs => ({
          'data-name': attrs.name,
        }),
      },
      settings: {
        default: null,
        parseHTML: el => {
          const s = el.getAttribute('data-settings')
          return s ? JSON.parse(s) : null
        },
        renderHTML: attrs => ({
          'data-settings': attrs.settings ? JSON.stringify(attrs.settings) : null,
        }),
      },
    }
  },

  parseHTML() {
    return [
      { tag: 'div[data-type="uiblock"]' },
    ]
  },

  renderHTML({ HTMLAttributes }) {
    return [
      'div',
      mergeAttributes(
        { 'data-type': 'uiblock', class: 'vnwa-uiblock' },
        HTMLAttributes
      ),
      // Hiển thị tên block + JSON settings trong editor
      `📦 UIBlock: ${HTMLAttributes['data-name'] || 'unknown'}` +
      (HTMLAttributes['data-settings'] ? ` [${JSON.stringify(HTMLAttributes['data-settings'])}]` : '')
    ]
  },


  addCommands() {
    return {
      insertUIBlock:
        (name: string, settings?: Record<string, any>) =>
        ({ chain }) => {
          return chain()
            .insertContent({
              type: this.name,
              attrs: { name, settings: settings || null },
            })
            .run()
        },
    }
  },
})
