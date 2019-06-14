<template>
    <div>
        <li class="list-group-item d-flex justify-content-between align-items-center" :class="className">
          <slot></slot>
          <!-- <span class="badge badge-primary badge-pill">chatty</span> -->
        </li>
        <small class="badge float-right" :class="badgeColor">You</small>
    </div>
</template>

<script>
export default {
    props: ['color'],
    computed: {
        className() {
            return 'list-group-item-'+this.color
        },
        badgeColor() {
            return 'badge-'+this.color
        }
    }
}
</script>
