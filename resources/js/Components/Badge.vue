<script setup>
import { XMarkIcon } from "@heroicons/vue/20/solid";
import { computed, onMounted, ref } from "vue";

const colors = [
  "gray", "sky", "rose",
  "pink", "fuchsia", "purple",
  "violet", "indigo", "blue",
  "cyan", "teal", "emerald",
  "green", "lime", "yellow",
  "amber", "orange", "red",
];
const randomColor = colors[Math.floor(Math.random() * colors.length)];

const props = defineProps({
  type: {
    type: String,
    default: "large",
  },
  color: {
    type: String,
    default: null,
  },
  closeable: {
    type: Boolean,
    default: false,
  },
});

defineEmits(["close"]);

const choosedColor = computed(() => {
  return props.color ?? randomColor;
});

const textSize = computed(() => {
  return {
    large: "px-2 text-base",
    medium: "px-2 text-sm",
    small: "px-2 text-xs",
  }[props.type];
});

const pickedColor = computed(() => {
  return {
    gray: "bg-gray-50  text-gray-600 ring-gray-500/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/20",
    sky: "bg-sky-50 text-sky-700 ring-sky-600/20 dark:bg-sky-500/10 dark:text-sky-400 dark:ring-sky-400/20",
    rose: "bg-rose-50 text-pink-600 ring-pink-700/10 dark:bg-rose-500/10 dark:text-rose-500 dark:ring-rose-500/20",
    pink: "bg-pink-50 text-pink-700 ring-pink-700/10 dark:bg-pink-400/10 dark:text-pink-400 dark:ring-pink-400/20",
    fuchsia: "bg-fuchsia-50 text-fuchsia-700 ring-fuchsia-700/10 dark:bg-fuchsia-400/10 dark:text-fuchsia-400 dark:ring-fuchsia-400/20",
    purple: "bg-purple-50 text-purple-700 ring-purple-700/10 dark:bg-purple-400/10 dark:text-purple-400 dark:ring-purple-400/30",
    violet: "bg-violet-50 text-violet-700 ring-violet-700/10 dark:bg-violet-400/10 dark:text-violet-400 dark:ring-violet-400/30",
    indigo: "bg-indigo-50 text-indigo-700 ring-indigo-700/10 dark:bg-indigo-400/10 dark:text-indigo-400 dark:ring-indigo-400/30",
    blue: "bg-blue-50 text-blue-700 ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30",
    cyan: "bg-cyan-50 text-cyan-700 ring-cyan-700/10 dark:bg-cyan-400/10 dark:text-cyan-400 dark:ring-cyan-400/20",
    teal: "bg-teal-50 text-teal-600 ring-teal-700/10 dark:bg-teal-400/10 dark:text-teal-400 dark:ring-teal-400/20",
    emerald: "bg-emerald-50 text-emerald-600 ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-400 dark:ring-emerald-400/20",
    green: "bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20",
    lime: "bg-lime-50 text-lime-700 ring-lime-600/20 dark:bg-lime-400/10 dark:text-lime-400 dark:ring-lime-400/20",
    yellow: "bg-yellow-50 text-yellow-800 ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20",
    amber: "bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-500 dark:ring-amber-500/20",
    orange: "bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-400/10 dark:text-orange-400 dark:ring-orange-400/20",
    red: "bg-red-50 text-red-700 ring-red-600/10 dark:bg-red-600/10 dark:text-red-500 dark:ring-red-500/20",
  }[choosedColor.value];
});

const closeableColor = computed(() => {
  return {
    gray: "hover:bg-gray-500/20 stroke-gray-700/50 group-hover:stroke-gray-700/75",
    sky: "hover:bg-sky-500/20 stroke-sky-700/50 group-hover:stroke-sky-700/75",
    rose: "hover:bg-rose-500/20 stroke-rose-700/50 group-hover:stroke-rose-700/75",
    pink: "hover:bg-pink-600/20 stroke-pink-800/50 group-hover:stroke-pink-800/75",
    fuchsia: "hover:bg-fuchsia-600/20 stroke-fuchsia-800/50 group-hover:stroke-fuchsia-800/75",
    purple: "hover:bg-purple-600/20 stroke-violet-700/50 group-hover:stroke-violet-700/75",
    violet: "hover:bg-violet-600/20 stroke-violet-700/50 group-hover:stroke-violet-700/75",
    indigo: "hover:bg-indigo-600/20 stroke-indigo-700/50 group-hover:stroke-indigo-700/75",
    blue: "hover:bg-blue-600/20 stroke-blue-800/50 group-hover:stroke-blue-800/75",
    cyan: "hover:bg-cyan-600/20 stroke-cyan-800/50 group-hover:stroke-cyan-800/75",
    teal: "hover:bg-teal-600/20 stroke-teal-800/50 group-hover:stroke-teal-800/75",
    emerald: "hover:bg-emerald-600/20 stroke-emerald-800/50 group-hover:stroke-emerald-800/75",
    green: "hover:bg-green-600/20 stroke-green-800/50 group-hover:stroke-green-800/75",
    lime: "hover:bg-lime-600/20 stroke-lime-800/50 group-hover:stroke-lime-800/75",
    yellow: "hover:bg-yellow-600/20 stroke-yellow-800/50 group-hover:stroke-yellow-800/75",
    amber: "hover:bg-amber-600/20 stroke-amber-800/50 group-hover:stroke-amber-800/75",
    orange: "hover:bg-orange-600/20 stroke-orange-800/50 group-hover:stroke-orange-800/75",
    red: "hover:bg-red-600/20 stroke-red-700/50 group-hover:stroke-red-700/75",
  }[choosedColor.value];
});
</script>

<template>
  <span
    :class="[
      textSize,
      pickedColor,
      'inline-flex items-center gap-0.5 whitespace-nowrap rounded-md py-1 font-medium ring-1 ring-inset',
    ]"
  >
    <slot />
    <button
      v-if="closeable"
      @click="$emit('close')"
      type="button"
      :class="[closeableColor, 'group relative -mr-1 h-3.5 w-3.5 rounded-sm']"
    >
      <span class="sr-only">Remover</span>
      <XMarkIcon class="h-3.5 w-3.5" />
      <span class="absolute -inset-1" />
    </button>
  </span>
</template>
