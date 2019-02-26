<template>
  <div>
    <v-data-table :items='items' item-key='id' hide-headers hide-actions :class='config.elevationClass'>
      <template slot="items" slot-scope="{ item }">
        <td>
          <SubjectGrade :item='item' />
        </td>
        <td>
          {{ item.lesson_count }} занятий
        </td>
        <td>
          {{ getData('teachers', item.teacher_id).names.short }}
        </td>
        <td class='text-md-right pa-0' width='180'>
          <v-btn flat color='primary'  small class='btn-td'>добавить отзыв</v-btn>
        </td>
      </template>
    </v-data-table>
    <!-- <ClientDialog ref='ClientDialog' /> -->
  </div>
</template>

<script>
// import ClientDialog from './Dialog'
export default {
  props: {
    items: {
      type: Array,
      required: false
    }
  },
  // components: { ClientDialog }
}
</script>