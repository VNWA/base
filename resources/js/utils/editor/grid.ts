import { Node, mergeAttributes } from '@tiptap/core'

export interface GridOptions {
  HTMLAttributes: Record<string, any>
}

declare module '@tiptap/core' {
  interface Commands<ReturnType> {
    grid: {
      setGrid: (desktopCols: number, mobileCols: number) => ReturnType
    }
  }
}

export const Grid = Node.create<GridOptions>({
  name: 'grid',

  group: 'block',

  content: 'col+',

  addOptions() {
    return {
      HTMLAttributes: {},
    }
  },

  addAttributes() {
    return {
      desktopCols: {
        default: 2,
        parseHTML: el => parseInt(el.getAttribute('data-desktop-cols') || '2', 10),
        renderHTML: attrs => ({ 'data-desktop-cols': attrs.desktopCols }),
      },
      mobileCols: {
        default: 1,
        parseHTML: el => parseInt(el.getAttribute('data-mobile-cols') || '1', 10),
        renderHTML: attrs => ({ 'data-mobile-cols': attrs.mobileCols }),
      },
    }
  },

  parseHTML() {
    return [{ tag: 'div[data-type="grid"]' }]
  },

  renderHTML({ node, HTMLAttributes }) {
    const { desktopCols, mobileCols } = node.attrs

    const gridCols: Record<number, string> = {
        1: 'grid-cols-1',
        2: 'grid-cols-2',
        3: 'grid-cols-3',
        4: 'grid-cols-4',
        5: 'grid-cols-5',
        6: 'grid-cols-6',
        7: 'grid-cols-7',
        8: 'grid-cols-8',
        9: 'grid-cols-9',
        10: 'grid-cols-10',
        11: 'grid-cols-11',
        12: 'grid-cols-12',
      }

      const twClass = [
        'w-full',
        'grid',
        gridCols[mobileCols] || 'grid-cols-1',
        `lg:${gridCols[desktopCols] || 'grid-cols-2'}`,
        'gap-3',
      ].join(' ')

    return [
      'div',
      mergeAttributes(HTMLAttributes, {
        'data-type': 'grid',
        class: twClass,
      }),
      0,
    ]
  },

  addCommands() {
    return {
      setGrid:
        (desktopCols = 2, mobileCols = 1) =>
        ({ chain }) => {
          return chain()
            .insertContent({
              type: this.name,
              attrs: { desktopCols, mobileCols },
              content: Array.from({ length: desktopCols }).map(() => ({
                type: 'col',
                content: [{ type: 'paragraph' }],
              })),
            })
            .run()
        },
    }
  },
})

export const Col = Node.create({
  name: 'col',

  content: 'block+',

  defining: true,

  parseHTML() {
    return [{ tag: 'div[data-type="col"]' }]
  },

  renderHTML({ HTMLAttributes }) {
    return [
      'div',
      mergeAttributes(HTMLAttributes, {
        'data-type': 'col',
        class: 'min-h-[20px] border border-dashed border-gray-300 p-2',
      }),
      0,
    ]
  },
})
