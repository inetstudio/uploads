<template>
    <div>
        <input ref="upload" type="file">
        <input :name="'media['+name+']['+collectionName+']'" type="hidden" :value="JSON.stringify(media)">
    </div>
</template>

<script>
  import MediaEditor from './MediaEditor/MediaEditor';
  import * as FilePond from 'filepond';
  import ru_RU from 'filepond/locale/ru-ru';
  import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
  import FilePondPluginFileEdit from '../plugins/filepond/filepond-plugin-file-edit/filepond-plugin-file-edit'
  import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
  import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

  import 'filepond/dist/filepond.css';
  import '../../sass/plugins/filepond/filepond-plugin-file-edit/filepond-plugin-file-edit.scss';
  import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

  export default {
    name: 'media-uploader',
    props: {
      name: {
          type: String,
          required: true
      },
      collectionName: {
        type: String,
        required: true
      },
      disks: {
        type: Object,
        default() {
          return {
            disk: 'default',
            conversions_disk: 'default',
          };
        }
      },
      mediaProp: {
        type: Array,
        default() {
          return [];
        }
      },
      editorProp: {
        type: Object,
        default() {
          return {
            crops: []
          };
        }
      },
      customPropertiesProp: {
        type: Array,
        default() {
          return [];
        }
      },
      uploaderOptions : {
        type: Object,
        default() {
          return {};
        }
      }
    },
    data() {
      let component = this;

      return {
        media: _.cloneDeep(component.mediaProp),
        mediaItemDummy: component.getMediaItemDummy(),
        uploader: undefined
      };
    },
    watch: {
      'media': {
        handler: function (newValue) {
          let component = this;

          component.$emit('update:media', {
            field: component.name,
            collectionName: component.collectionName,
            media: newValue
          });
        },
        deep: true
      }
    },
    methods: {
      getMediaItemDummy() {
        let component = this;

        let emptyMediaItem = {
          id: 0,
          serverId: '',
          collection_name: component.collectionName,
          name: '',
          file_name: '',
          mime_type: '',
          disk: component.disks.disk,
          conversions_disk: component.disks.conversions_disk,
          manipulations: {},
          custom_properties: {}
        };

        component.customPropertiesProp.forEach((property) => {
          emptyMediaItem.custom_properties[property.name] = '';
        });

        return emptyMediaItem;
      },
      getMediaItemByFile(file) {
        let component = this;

        return _.find(component.media, (mediaItem) => {
          return (
              mediaItem.serverId === file.serverId ||
              mediaItem.id === file.serverId
          );
        });
      },
      add(file) {
        let component = this;

        let mediaItem = _.cloneDeep(component.mediaItemDummy);

        let filePath = file.serverId.split('/');

        mediaItem.serverId = filePath[0];
        mediaItem.name = file.filename;
        mediaItem.file_name = filePath[1] || '';
        mediaItem.mime_type = file.fileType;

        component.media.push(mediaItem);

      },
      remove(file) {
        let component = this;

        if (! file.serverId) {
          return;
        }

        let filePath = file.serverId.toString().split('/');

        component.media = _.remove(component.media, (mediaItem) => {
          if (mediaItem.serverId !== undefined && mediaItem.serverId !== '') {
            return mediaItem.serverId !== filePath[0];
          } else if (mediaItem.id !== undefined && mediaItem.id !== 0) {
            return mediaItem.id !== parseInt(filePath[0]);
          }

          return true;
        });
      }
    },
    mounted() {
      let component = this;

      const input = component.$refs.upload;

      let filepondOptions = {
        className: 'media-uploader',
        credits: false,
        allowMultiple: false,
        chunkUploads: true,
        chunkForce: true,
        chunkSize: 500000,
        files: [],
        imagePreviewHeight: 256,
        labelTapToUndo: '',
        styleFileEditButtonEditItemPosition: 'center',
        allowFileEdit: {
          check: (item) => {
            let regex = new RegExp('^image/[-\\w.]+$');

            return component.customPropertiesProp.length > 0 ||
                ((! _.isEmpty(component.editorProp)) && regex.test(item.fileType) && item.fileType !== 'image/gif');
          }
        },
        fileEditEditor: {
          open: (item) => {
            let mediaItem = component.getMediaItemByFile(item);

            if (mediaItem === undefined) {
              return;
            }

            component.$modal.show(
                MediaEditor,
                {
                  mediaItemProp: mediaItem,
                  customPropertiesProp: component.customPropertiesProp,
                  editorProp: component.editorProp,
                  fileItemProp: item,
                  save: (result) => {
                    Object.assign(mediaItem, result);
                  }
                },
                {
                  adaptive: true,
                  height: 'auto',
                  minWidth: 800
                }
            );
          }
        },
        server: {
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          patch: {
            url: route('inetstudio.uploads-package.uploads.back.patch', ['serverId'])
          },
          process: {
            url: route('inetstudio.uploads-package.uploads.back.process')
          },
          revert: {
            url: route('inetstudio.uploads-package.uploads.back.revert')
          },
          restore: {
            url: route('inetstudio.uploads-package.uploads.back.restore', ['serverId'])
          },
          load: {
            url: route('inetstudio.uploads-package.uploads.back.load', ['id'])
          },
          fetch: {
            url: route('inetstudio.uploads-package.uploads.back.fetch', ['url'])
          }
        },
        onprocessfile: (error, file) => {
          if (error !== null) {
            return;
          }

          if(_.isString(file.source)) {
            return;
          }

          component.add(file);
        },
        onprocessfilerevert: (file) => {
          component.remove(file);
        },
        onremovefile: (error, file) => {
          if (error !== null) {
            return;
          }

          component.remove(file);
        }
      };

      filepondOptions = _.merge(filepondOptions, component.uploaderOptions);

      filepondOptions.className += (filepondOptions.allowMultiple) ? '-multiple' : '-single';

      FilePond.registerPlugin(FilePondPluginFileValidateType);
      FilePond.registerPlugin(FilePondPluginFileEdit);
      FilePond.registerPlugin(FilePondPluginImageExifOrientation);
      FilePond.registerPlugin(FilePondPluginImagePreview);
      FilePond.setOptions(ru_RU);

      component.media.forEach((mediaItem) => {
        if (mediaItem.serverId !== undefined && mediaItem.serverId !== '') {
          filepondOptions.files.push({
            source: mediaItem.serverId,
            options: {
              type: 'limbo'
            }
          });
        } else if (mediaItem.id !== undefined && mediaItem.id !== 0) {
          filepondOptions.files.push({
            source: mediaItem.id,
            options: {
              type: 'local'
            }
          });
        }
      });

      component.uploader = FilePond.create(input, filepondOptions);
    }
  }
</script>

<style scoped>
</style>
