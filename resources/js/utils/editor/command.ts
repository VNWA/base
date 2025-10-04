import { Editor } from "@tiptap/core";

export function insertGrid(editor: Editor, cols: number, items: number) {
    editor.chain().focus().insertContent({
      type: "grid",
      attrs: { cols },
      content: Array.from({ length: items }, () => ({
        type: "gridItem",
        content: [{ type: "paragraph", content: [{ type: "text", text: "Nội dung" }] }],
      })),
    }).run();
  }