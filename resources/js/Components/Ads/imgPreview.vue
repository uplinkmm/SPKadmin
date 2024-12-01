<template lang="">
  <div v-if="imgPreview" class="mt-7 flex flex-nowrap">
      <div class="mt-3 file-name-box">
          <div class="relative w-fit">
              <div class="item-img-container relative w-fit">
                  <button
                      class="cancel-btn"
                      @click.prevent="deleteImage(type)"
                  >
                      X
                  </button>
                  <img alt="" :src="imgPreview"  class=" " />
              </div>
          </div>
      </div>
  </div>
</template>
<script>
//
export default {
  name: "ImagePreview",
  props: {
      imgPreview: {},
      deleteImage: {
          type: Function,
      },
      type: {
          type: String,
          default: "icon",
      },
  },
};
</script>
<style>
.item-img-container img {
  width: 8rem;
  height: auto;
  border-radius: 2px;
}
</style>
