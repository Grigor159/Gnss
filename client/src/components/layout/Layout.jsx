import React, { Suspense } from "react"
import { Header } from "../header/Header"
import { AutoScroll } from "../../helpers/autoScroll"
import { HelmetAsync } from "../helmetAsync/HelmetAsync"
import { Loader } from "../loader/Loader"
import { Outlet } from 'react-router-dom'
import { Footer } from "../footer/Footer"

const Layout = () => {
  return (
    <>
      <Header />
      <AutoScroll />
      <HelmetAsync />
      <Suspense fallback={<Loader />}>
        <Outlet />
      </Suspense >
      <Footer />
    </>
  )
}

export default Layout