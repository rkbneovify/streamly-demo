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
    @IBOutlet weak var turnOffImageView: UIImageView!
    @IBOutlet weak var deviceNameLabel: UILabel!
    @IBOutlet weak var deviceTypeLabel: UILabel!
    
    static let identifier = String(describing: DeviceInfoGridCell.self)
    override func awakeFromNib() {
        super.awakeFromNib()
        configureCell()
    }
    
    private func configureCell() {
        deviceMainTypeLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 25.0)
        deviceNameLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 20.0)
        deviceTypeLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 20.0)
        self.bgView.layer.cornerRadius = 10
        self.bgView.borderColor = .white
        self.bgView.borderWidth = 1.0
        turnOffImageView.isHidden = true
        deviceNameLabel.isHidden = true
        deviceTypeLabel.isHidden = true
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
                self.bgView.borderColor = .white
                self.turnOffImageView.isHidden = true
                self.deviceNameLabel.isHidden = true
                self.deviceTypeLabel.isHidden = true
                self.deviceImage.isHidden = false
                
            }
            else {
                
                self.deviceImage.isHidden = true
                self.deviceNameLabel.isHidden = false
                self.deviceTypeLabel.isHidden = false
                self.turnOffImageView.isHidden = false
             }
        }, completion: nil)
    }
}
