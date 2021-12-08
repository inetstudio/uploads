<template>
    <div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label font-bold">{{ label }}</label>
            <div class="col-sm-10">
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
                />
              </template>
            </div>
        </div>
        <div class="hr-line-dashed"></div>
    </div>
</template>

<script>
  export default {
    name: 'media-field',
    components: {
      'media-uploader': () => import('./MediaUploader.vue')
    },
    props: {
      label: {
        type: String,
        default: ''
      },
      name: {
          type: String,
          required: true
      },
      mediaCollections: {
        type: Object,
        default() {
          return {};
        }
      }
    }
  }
</script>

<style scoped>
</style>
