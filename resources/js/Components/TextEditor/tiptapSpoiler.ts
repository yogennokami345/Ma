import { mergeAttributes, Node, VueNodeViewRenderer } from "@tiptap/vue-3"
import SpoilerNodeView from './SpoilerNodeView.vue'

declare module '@tiptap/core' {
    interface Commands<ReturnType> {
        spoiler: {
            toggleSpoiler: () => ReturnType,
        }
    }
}

export default Node.create({
    name: 'spoiler',

    group: 'inline',

    inline: true,

    content: 'inline',

    defining: true,

    addAttributes() {
        return {
            spoiler: {
                default: 'true',
                parseHTML: (el) => (el as HTMLSpanElement).getAttribute('data-spoiler'),
                renderHTML: ({ spoiler }) => ({ 'data-spoiler': spoiler }),
            },
        }
    },

    parseHTML() {
        return [
            {
                tag: 'span.spoiler',
            },
        ];
    },

    renderHTML({ HTMLAttributes }) {
        return ['span', mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0]
    },

    addCommands() {
        return {
            toggleSpoiler: () => ({ chain, state }: any) => {
                const { from, to } = state.selection;
                const selectionText = state.doc.textBetween(from, to);
                let hasSpoiler = false;

                state.doc.nodesBetween(from, to, (node: any) => {
                    if (node.type.name === this.name) {
                        hasSpoiler = true;
                    }
                });

                if (hasSpoiler) {
                    return chain()
                        .focus()
                        .insertContentAt({ from, to }, selectionText)
                        .run();
                } else {
                    return chain()
                        .focus()
                        .insertContentAt({ from, to },
                            {
                                type: this.name,
                                content: [
                                    {
                                        type: 'text',
                                        text: selectionText,
                                    },
                                ],
                            },
                        )
                        .run();
                }
            }
        }
    },

    addNodeView() {
        // @ts-ignore
        return VueNodeViewRenderer(SpoilerNodeView)
    }
})