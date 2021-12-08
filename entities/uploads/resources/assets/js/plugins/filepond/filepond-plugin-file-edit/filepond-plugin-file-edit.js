(function(global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined'
      ? (module.exports = factory())
      : typeof define === 'function' && define.amd
      ? define(factory)
      : ((global = global || self), (global.FilePondPluginFileEdit = factory()));
})(this, function() {
  'use strict';

  const plugin = (fpAPI) => {
    const { addFilter, utils, views } = fpAPI;
    const { Type, createRoute } = utils;

    const fileActionButton = views.fileActionButton;

    addFilter('CREATE_VIEW', (viewAPI) => {
      // get reference to created view
      const { is, view, query } = viewAPI;

      if (!is('file')) {
        return;
      }

      let editor = query('GET_FILE_EDIT_EDITOR');
      if (!editor) return;

      const openEditor = function openEditor(_ref4) {
        let root = _ref4.root,
            props = _ref4.props;

        let id = props.id;

        let item = root.query('GET_ITEM', id);
        if (!item) return;

        editor.open(item);
      };

      const didLoadItem = ({ root, props }) => {
        const { id } = props;
        const item = query('GET_ITEM', id);

        if (! item || item.archived) {
          return;
        }

        const allowEdit = root.query('GET_ALLOW_FILE_EDIT');

        if (! allowEdit.check(item)) {
          return;
        }

        root.ref.handleEdit = function(e) {
          e.stopPropagation();
          root.dispatch('EDIT_ITEM', { id: id });
        };

        let buttonView = view.createChildView(fileActionButton, {
          label: 'edit',
          icon: query('GET_FILE_EDIT_ICON_EDIT'),
          opacity: 1
        });

        // edit item classname
        buttonView.element.classList.add('filepond--action-edit-item');
        buttonView.element.dataset.align = query(
            'GET_STYLE_FILE_EDIT_BUTTON_EDIT_ITEM_POSITION'
        );

        buttonView.on('click', root.ref.handleEdit);

        root.ref.buttonEditItem = view.appendChildView(buttonView);
      };

      view.registerDestroyer(function(_ref7) {
        let root = _ref7.root;

        if (root.ref.buttonEditItem) {
          root.ref.buttonEditItem.off('click', root.ref.handleEdit);
        }
      });

      let routes = {
        EDIT_ITEM: openEditor,
        DID_LOAD_ITEM: didLoadItem
      };

      view.registerWriter(createRoute(routes));

      /*view.registerWriter(
          createRoute(
              {
                DID_LOAD_ITEM: didLoadItem,
              },
              ({ root, props }) => {
                const { id } = props;
                const item = query('GET_ITEM', id); // don't do anything while hidden

                if (root.rect.element.hidden) return;
              }
          )
      );*/
    });

    return {
      options: {
        allowFileEdit: [true, Type.OBJECT],

        styleFileEditButtonEditItemPosition: ['bottom center', Type.STRING],

        fileEditIconEdit: [
          '<svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M8.5 17h1.586l7-7L15.5 8.414l-7 7V17zm-1.707-2.707l8-8a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1 0 1.414l-8 8A1 1 0 0 1 10.5 19h-3a1 1 0 0 1-1-1v-3a1 1 0 0 1 .293-.707z" fill="currentColor" fill-rule="nonzero"/></svg>',
          Type.STRING
        ],

        fileEditEditor: [null, Type.OBJECT]
      },
    };
  };

  const isBrowser =
      typeof window !== 'undefined' && typeof window.document !== 'undefined';

  if (isBrowser) {
    document.dispatchEvent(
        new CustomEvent('FilePond:pluginloaded', {
          detail: plugin,
        })
    );
  }

  return plugin;
});
