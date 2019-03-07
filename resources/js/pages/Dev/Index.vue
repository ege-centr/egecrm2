<template>
  <div>
    <div class='headline'>DEV</div>
    <display-list>
      <template slot='allo'>
        <div class='green--text'>working!</div>
      </template>
    </display-list>
  </div>
</template>



<script>
import DisplayList from '@/components/UI/DisplayList'

export default {
  components: { DisplayList },

  data() {
    return {
     
    }
  },

 
}
</script>
