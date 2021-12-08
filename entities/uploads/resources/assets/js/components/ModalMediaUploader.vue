<template>
  <div id="media_uploader_component_modal" class="vue-inmodal">
    <div class="vue-modal-dialog vue-modal-lg">
      <div class="vue-modal-content">

        <div class="vue-modal-header">
          <h4 class="vue-modal-title">Медиа</h4>
        </div>

        <div class="vue-modal-body">
          <div class="ibox">
            <div class="ibox-content no-borders">
              <template
                  v-for="(collection, collectionName) in mediaCollections"
              >
                <h4 v-if="collection.title !== ''">{{ collection.title}}</h4>
                <media-uploader
                    :name="name"
                    :collection-name="collectionName"
                    :disks="collection.disks"
                    :media-prop="collection.media"
                    :editor-prop="collection.editor"
                    :custom-properties-prop="collection.customProperties"
                    :uploader-options="collection.uploaderOptions"
                    v-on:update:media="updateMedia"
                />
              </template>
            </div>
          </div>
        </div>

        <div class="vue-modal-footer">
          <button type="button" class="btn btn-white" @click.prevent="$emit('close')">Закрыть</button>
          <a href="#" class="btn btn-primary" @click.prevent="saveMedia">Сохранить</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MediaUploader from './MediaUploader';

export default {
  name: 'media-field',
  components: [
    MediaUploader
  ],
  props: {
    name: {
      type: String,
      required: true
    },
    mediaCollections: {
      type: Object,
      default() {
        return {};
      }
    },
    save: {
      type: Function,
      default() {
        this.$emit('close');
      }
    }
  },
  data() {
    return {
      media: _.cloneDeep(this.mediaCollections)
    };
  },
  methods: {
    updateMedia(payload) {
      let component = this;

      component.$set(component.media[payload.collectionName], 'media', payload.media);
    },
    saveMedia() {
      let component = this;

      component.save(component.media);
      component.$emit('close');
    }
  }
}
</script>

<style scoped>
</style>
