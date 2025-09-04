<template>
  <section class="hero-section">
    <!-- Carrossel Principal -->
    <div class="hero-carousel">
      <swiper
        :modules="[SwiperAutoplay, SwiperPagination, SwiperNavigation]"
        :slides-per-view="1"
        :loop="true"
        :space-between="0"
        :centered-slides="true"
        :grab-cursor="true"
        :effect="'slide'"
        :speed="800"
        :autoplay="{
          delay: 5000,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        }"
        :pagination="{ 
          clickable: true,
          dynamicBullets: true
        }"
        :navigation="{
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        }"
        :breakpoints="{
          0: { centeredSlides: false, spaceBetween: 12 },
          640: { centeredSlides: true, spaceBetween: 0 }
        }"
        class="hero-swiper"
      >
        <swiper-slide v-for="(slide, index) in heroSlides" :key="index" class="hero-slide">
          <div class="slide-inner">
            <div class="slide-content">
              <div class="slide-text">
                <h1 class="slide-title">{{ slide.title }}</h1>
                <p class="slide-subtitle">{{ slide.subtitle }}</p>
              </div>
              <div class="slide-image">
                <div class="slide-icon-container" :style="{ background: slide.color }">
                  <component :is="slide.icon" class="slide-icon" />
                </div>
              </div>
            </div>
          </div>
        </swiper-slide>
      </swiper>
    </div>
    
    <!-- Separador Visual Elegante -->
    <div class="section-divider">
      <div class="divider-line"></div>
      <div class="divider-line"></div>
    </div>

    <!-- Categorias Rápidas -->
    <div class="quick-categories">
      <div class="container">
        <div class="categories-grid">
          <div 
            v-for="category in quickCategories" 
            :key="category.id"
            class="category-card"
            @click="navigateToCategory(category.id)"
          >
            <div class="category-icon">
              <component :is="category.icon" />
            </div>
            <h3 class="category-name">{{ category.name }}</h3>
            <p class="category-description">{{ category.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/pagination'
import 'swiper/css/navigation'
import {
  DevicePhoneMobileIcon,
  ComputerDesktopIcon,
  SpeakerWaveIcon,
  RectangleGroupIcon,
  PhotoIcon,
  CommandLineIcon,
  SparklesIcon,
  TagIcon,
  TruckIcon
} from '@heroicons/vue/24/outline'

const SwiperAutoplay = Autoplay
const SwiperPagination = Pagination
const SwiperNavigation = Navigation

const heroSlides = [
  {
    title: 'Tecnologia do Futuro',
    subtitle: 'Descubra os últimos lançamentos em smartphones, notebooks e muito mais',
    icon: SparklesIcon,
    color: 'linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%)'
  },
  {
    title: 'Ofertas Imperdíveis',
    subtitle: 'Até 50% de desconto em produtos selecionados',
    icon: TagIcon,
    color: 'linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%)'
  },
  {
    title: 'Entrega Rápida',
    subtitle: 'Receba em até 24h com frete grátis',
    icon: TruckIcon,
    color: 'linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%)'
  }
]

const quickCategories = [
  {
    id: 'smartphones',
    name: 'Smartphones',
    description: 'Galaxy S23, iPhone 15 Pro',
    icon: DevicePhoneMobileIcon
  },
  {
    id: 'laptops',
    name: 'Notebooks',
    description: 'Dell Inspiron, Acer Nitro Gaming',
    icon: ComputerDesktopIcon
  },
  {
    id: 'headphones',
    name: 'Fones de Ouvido',
    description: 'Sony WH-1000XM4, Gaming RGB',
    icon: SpeakerWaveIcon
  },
  {
    id: 'gaming',
    name: 'Gaming',
    description: 'Mouse RGB, PC Gamer PICHAU',
    icon: CommandLineIcon
  },
  {
    id: 'cameras',
    name: 'Câmeras',
    description: 'DSLR e Action Cams',
    icon: PhotoIcon
  },
  {
    id: 'tvs',
    name: 'Smart TVs',
    description: 'LG 4K UHD com webOS',
    icon: RectangleGroupIcon
  }
]

const emit = defineEmits<{
  categorySelected: [categoryId: string]
}>()

const navigateToCategory = (categoryId: string) => {
  emit('categorySelected', categoryId)
}
</script>

<style scoped>
.hero-section {
  position: relative;
}

/* Garantir contraste em TODOS os elementos da seção hero */
.hero-section * {
  color: var(--text-primary);
}

/* Garantir contraste específico nos cards das categorias */
.category-card {
  background: var(--card-bg) !important;
  border-color: var(--card-border) !important;
}

.category-card * {
  color: var(--text-primary) !important;
}

.category-name {
  color: var(--text-primary) !important;
}

.category-description {
  color: var(--text-secondary) !important;
}

.category-icon {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%) !important;
  color: var(--text-inverse) !important;
}

.hero-section h1, .hero-section h2, .hero-section h3, .hero-section h4, .hero-section h5, .hero-section h6 {
  color: var(--text-primary);
}

.hero-section p, .hero-section span, .hero-section div, .hero-section label {
  color: var(--text-primary);
}

.hero-section button {
  color: var(--text-primary);
}

.hero-section input, .hero-section select, .hero-section textarea {
  color: var(--text-primary);
  background-color: var(--input-bg);
  border-color: var(--input-border);
}

.hero-carousel {
  height: 600px;
  background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
  overflow: visible;
  position: relative;
  padding: 40px 0;
  border-bottom: 1px solid var(--border-primary);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.hero-carousel::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
  opacity: 0.6;
}

.hero-carousel::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
  opacity: 0.3;
}

/* Prevenir scroll durante transições do carrossel */
.hero-carousel {
  overscroll-behavior: none;
}

.hero-swiper {
  height: 100%;
  width: 100%;
  overflow: visible;
  overscroll-behavior: none;
}

:deep(.swiper-wrapper) {
  align-items: center;
  overscroll-behavior: none;
}

:deep(.swiper-slide) {
  overscroll-behavior: none;
}

/* O slide mantém caixa estável, quem escala é o wrapper interno */
.hero-slide {
  box-sizing: border-box;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 80px;
  height: 100%;
  min-height: 520px;
  width: 100%;
  overflow: hidden;
  overscroll-behavior: none;
}

.slide-inner {
  transition: transform 0.5s ease, opacity 0.5s ease;
  transform: scale(0.9);
  opacity: 0.6;
}

/* Estados dirigidos pelo Swiper mas aplicados no INNER */
:deep(.swiper-slide-active) .slide-inner {
  transform: scale(1);
  opacity: 1;
}

:deep(.swiper-slide-prev) .slide-inner,
:deep(.swiper-slide-next) .slide-inner {
  transform: scale(0.95);
  opacity: 0.8;
}

.hero-slide {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 80px;
  height: 100%;
  min-height: 520px;
}

.slide-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
  max-width: 1200px;
  width: 100%;
  position: relative;
  z-index: 2;
}

@media (max-width: 768px) {
  .slide-content {
    grid-template-columns: 1fr;
    gap: 24px;
    text-align: center;
    justify-items: center;
  }
}

.slide-text {
  color: var(--text-inverse);
  text-align: left;
}

@media (max-width: 768px) {
  .slide-text {
    text-align: center;
  }
}

.slide-title {
  font-size: 48px;
  font-weight: 900;
  margin: 0 0 24px 0;
  line-height: 1.1;
  letter-spacing: -1px;
}

.slide-subtitle {
  font-size: 20px;
  margin: 0 0 32px 0;
  opacity: 0.9;
  line-height: 1.5;
}

.slide-actions {
  display: flex;
  gap: 16px;
}

.btn-primary {
  background: var(--primary-color);
  color: var(--text-inverse);
  border: none;
  padding: 16px 32px;
  border-radius: 32px;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  min-width: 140px;
  white-space: nowrap;
}

.btn-primary:hover {
  background: var(--primary-hover);
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.btn-secondary {
  background: transparent;
  color: var(--text-inverse);
  border: 2px solid var(--text-inverse);
  padding: 14px 30px;
  border-radius: 32px;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  min-width: 140px;
  white-space: nowrap;
}

.btn-secondary:hover {
  background: var(--text-inverse);
  color: var(--text-primary);
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.slide-image {
  display: flex;
  justify-content: center;
  align-items: center;
}

.slide-icon-container {
  width: 200px;
  height: 200px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: var(--shadow-xl);
  animation: float 6s ease-in-out infinite;
}

.slide-icon {
  width: 80px;
  height: 80px;
  color: var(--text-inverse);
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

/* Swiper Customization */
:deep(.swiper-pagination) {
  bottom: 30px;
}

:deep(.swiper-pagination-bullet) {
  background: var(--text-inverse);
  opacity: 0.5;
  width: 12px;
  height: 12px;
  margin: 0 6px;
  transition: all 0.3s ease;
}

:deep(.swiper-pagination-bullet-active) {
  opacity: 1;
  transform: scale(1.2);
  background: var(--primary-color);
}

:deep(.swiper-button-next),
:deep(.swiper-button-prev) {
  color: var(--text-inverse);
  background: transparent;
  width: 48px;
  height: 48px;
  border-radius: 50%;
  transition: all 0.3s ease;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

:deep(.swiper-button-next::after),
:deep(.swiper-button-prev::after) {
  font-size: 20px;
  font-weight: bold;
}

:deep(.swiper-button-next:hover),
:deep(.swiper-button-prev:hover) {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.8);
  transform: scale(1.1);
}

/* Quick Categories */
.quick-categories {
  background: white;
  padding: 80px 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

.categories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 32px;
}

.category-card {
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: 16px;
  padding: 32px 24px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.category-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-xl);
  border-color: var(--primary-color);
}

.category-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 24px;
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-inverse);
}

.category-icon svg {
  width: 32px;
  height: 32px;
}

.category-name {
  font-size: 20px;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0 0 12px 0;
}

.category-description {
  font-size: 14px;
  color: var(--text-secondary);
  margin: 0;
  line-height: 1.5;
}

/* Separador Visual Elegante */
.section-divider {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 60px 0;
  position: relative;
  padding: 0 20px;
}

.divider-line {
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, transparent 0%, var(--border-primary) 50%, transparent 100%);
  max-width: 200px;
  opacity: 0.8;
}

.divider-icon {
  position: relative;
  z-index: 1;
  background: var(--bg-primary);
  border: 2px solid var(--border-primary);
  border-radius: 50%;
  padding: 12px;
  margin: 0 24px;
  box-shadow: var(--shadow-md);
  transition: all 0.3s ease;
}

.divider-icon:hover {
  transform: scale(1.1);
  box-shadow: var(--shadow-lg);
  border-color: var(--primary-color);
}

.divider-icon svg {
  color: var(--primary-color);
  width: 20px;
  height: 20px;
}

@media (max-width: 1024px) {
  .slide-content {
    grid-template-columns: 1fr;
    text-align: center;
    gap: 40px;
  }
  
  .slide-title {
    font-size: 36px;
  }
  
  .slide-subtitle {
    font-size: 18px;
  }
  
  .slide-icon-container {
    width: 150px;
    height: 150px;
  }
  
  .slide-icon {
    width: 60px;
    height: 60px;
  }
}

@media (max-width: 768px) {
  .hero-carousel {
    height: 450px;
  }
  
  .section-divider {
    margin: 40px 0;
    padding: 0 16px;
  }
  
  .divider-icon {
    padding: 10px;
    margin: 0 16px;
  }
  
  .divider-icon svg {
    width: 18px;
    height: 18px;
  }
  
  .hero-slide {
    padding: 0 16px;
    min-height: 370px;
  }
  
  .slide-content {
    gap: 24px;
    text-align: center;
    justify-items: center;
  }
  
  .slide-text {
    order: 2;
    text-align: center;
  }
  
  .slide-image {
    order: 1;
    margin-bottom: 20px;
  }
  
  .slide-title {
    font-size: 24px;
    margin-bottom: 16px;
  }
  
  .slide-subtitle {
    font-size: 14px;
    margin-bottom: 24px;
  }
  
  .slide-actions {
    flex-direction: column;
    align-items: center;
    gap: 12px;
  }
  
  .btn-primary,
  .btn-secondary {
    width: 100%;
    max-width: 240px;
    padding: 12px 16px;
    font-size: 14px;
  }
  
  .slide-icon-container {
    width: 120px;
    height: 120px;
  }
  
  .slide-icon {
    width: 50px;
    height: 50px;
  }
  
  .categories-grid {
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 20px;
  }
  
  .category-card {
    padding: 20px 12px;
  }
  
  .category-icon {
    width: 48px;
    height: 48px;
    margin-bottom: 16px;
  }
  
  .category-icon svg {
    width: 24px;
    height: 24px;
  }
  
  .category-name {
    font-size: 16px;
    margin-bottom: 8px;
  }
  
  .category-description {
    font-size: 12px;
  }
}

@media (max-width: 480px) {
  .hero-carousel {
    height: 400px;
  }
  
  .hero-slide {
    padding: 0 12px;
    min-height: 320px;
  }
  
  .slide-content {
    gap: 16px;
  }
  
  .slide-text {
    order: 2;
    text-align: center;
  }
  
  .slide-image {
    order: 1;
    margin-bottom: 16px;
  }
  
  .slide-title {
    font-size: 20px;
    margin-bottom: 12px;
  }
  
  .slide-subtitle {
    font-size: 13px;
    margin-bottom: 20px;
  }
  
  .slide-icon-container {
    width: 100px;
    height: 100px;
  }
  
  .slide-icon {
    width: 40px;
    height: 40px;
  }
  
  .btn-primary,
  .btn-secondary {
    max-width: 200px;
    padding: 10px 14px;
    font-size: 13px;
  }
  
  .categories-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
  
  .category-card {
    padding: 16px 8px;
  }
}
</style>
