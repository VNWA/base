// GridItem.ts
import { Node, mergeAttributes } from "@tiptap/core";

export const GridItem = Node.create({
  name: "gridItem",

  group: "block",
  content: "block+",

  parseHTML() {
    return [{ tag: "div[data-type=grid-item]" }];
  },

  renderHTML({ HTMLAttributes }) {
    return ["div", mergeAttributes(HTMLAttributes, { "data-type": "grid-item", style: "padding:4px; border:1px dashed #ccc;" }), 0];
  },
});
