<template>
    <div>
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
          <a class="navbar-brand" href="/admin">Panel Admin</a>
          <a class="btn btn-light ms-auto" href="/dashboard">Salir</a>
        </div>
      </nav>
      <div class="container mt-4">
        <slot />
      </div>
    </div>
  </template>
  