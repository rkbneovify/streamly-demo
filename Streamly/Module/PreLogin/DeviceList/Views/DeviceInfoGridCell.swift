//
//  DeviceInfoGridCell.swift
//  Streamly
//
//  Created by Aditya Joshi on 26/07/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import UIKit
import CoreImage

class DeviceInfoGridCell: UICollectionViewCell {
    @IBOutlet weak var deviceMainTypeLabel: UILabel!
    @IBOutlet weak var bgView: UIView!
    @IBOutlet weak var deviceImage: UIImageView!
    @IBOutlet weak var turnOFFImageView: UIImageView!
    @IBOutlet weak var blureBGVIew: UIVisualEffectView!
    @IBOutlet weak var lowerBGView: UIView!
    @IBOutlet weak var blurImageView: UIImageView!
    @IBOutlet weak var deviceNameLabel: UILabel!
    @IBOutlet weak var deviceTypeLabel: UILabel!
    
    static let identifier = String(describing: DeviceInfoGridCell.self)
    override func awakeFromNib() {
        super.awakeFromNib()
        configureCell()
    }
    
    private func configureCell() {
        deviceMainTypeLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 25.0)
        deviceNameLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 18.0)
        deviceTypeLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 18.0)
        bgView.layer.cornerRadius = 10
        blurImageView.layer.cornerRadius = 10
        self.bgView.borderColor = .white
        lowerBGView.layer.maskedCorners = [.layerMaxXMaxYCorner, .layerMinXMaxYCorner]
        lowerBGView.backgroundColor = .darkGray
        lowerBGView.layer.cornerRadius = 10
        self.lowerBGView.borderColor = .white
        turnOFFImageView.isHidden = true
        self.blurImageView.backgroundColor = .clear//.white.withAlphaComponent(0.50)
        self.blurImageView.isHidden = true
        lowerBGView.isHidden = true
        blureBGVIew.isHidden = true
    }
    
    func setupData(model: DeviceInfo) {
        deviceMainTypeLabel.text = model.deviceName
        deviceNameLabel.text = "Device Name: \(model.personaldevicename ?? "")"
        deviceTypeLabel.text = "Device Type: \(model.deviceType ?? "")"
        deviceImage.sd_setImage(with: URL(string: model.deviceImg ?? ""), completed: nil)
    }
    // MARK: UIFocusEnvironment
    
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        /*
         Update the label's alpha value using the `UIFocusAnimationCoordinator`.
         This will ensure all animations run alongside each other when the focus
         changes.
         */
        coordinator.addCoordinatedAnimations({
            if !self.isFocused {
//                self.bgView.backgroundColor = UIColor.init(red: 89/255, green: 97/255, blue: 100/255, alpha: 1.0)
                self.bgView.borderWidth = 1.0
                self.turnOFFImageView.isHidden = true
                self.blureBGVIew.isHidden = true
                self.lowerBGView.isHidden = true
            }
            else {
                self.lowerBGView.isHidden = false
                self.blureBGVIew.isHidden = false
                self.turnOFFImageView.isHidden = false
                self.bgView.borderWidth = 0
            }
        }, completion: nil)
    }
}
