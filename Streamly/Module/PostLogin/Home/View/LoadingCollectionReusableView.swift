//
//  LoadingCollectionReusableView.swift
//  Streamly
//
//  Created by Aditya Joshi on 14/12/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

class LoadingCollectionReusableView: UICollectionReusableView {
    let activityIndicator: UIActivityIndicatorView = {
        let av = UIActivityIndicatorView(style: .medium)
        av.hidesWhenStopped = true
        av.translatesAutoresizingMaskIntoConstraints = false
        return av
    }()

    override init(frame: CGRect) {
        super.init(frame: frame)
        self.setupUI()
    }

    required init(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)!
        self.setupUI()
    }

    func setupUI() {
        self.addSubview(activityIndicator)
        activityIndicator.startAnimating()
        NSLayoutConstraint.activate([
            activityIndicator.centerYAnchor.constraint(equalTo: centerYAnchor),
            activityIndicator.centerXAnchor.constraint(equalTo: centerXAnchor)
        ])
    }
}
