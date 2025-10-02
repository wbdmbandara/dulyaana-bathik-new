import React from 'react'
import { Link } from 'react-router-dom'

import Header from "./components/common/Header";
import PageTitle from "./components/common/PageTitle";
import AboutCompany from "./components/common/AboutCompany";
import Stats from "./components/common/Stats";
import Testimonials from "./components/common/Testimonials";
import Footer from "./components/common/Footer";

const About = () => {
  return (
    <div>
        <Header activeMenu="about"/>
        <PageTitle title="About" />
        <AboutCompany />
        <Stats />
        <Testimonials />
        <Footer />
    </div>
  )
}
export default About