import React from 'react'

function PageTitle({ title }) {
  return (
		<div className="page-title light-background">
			<div className="container">
				<nav className="breadcrumbs">
					<ol>
						<li>
							<a href="/">Home</a>
						</li>
						<li className="current">{title}</li>
					</ol>
				</nav>
				<h1>{title}</h1>
			</div>
		</div>
  );
}

export default PageTitle